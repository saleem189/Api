<?php

namespace App\Http\Controllers;

use App\Mail\UserMailChange;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Transformers\UserTransformerClass;
// use App\Transformers\UserTransformerClass;
use Cloudinary\Api\Provisioning\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Null_;

class UserController extends ApiController
{
    use ApiResponser;

    /**
     * Calling Constructor and parent constructor
     * and assiging middleware to specific function
     */
    public function __construct()
    {
        parent::__construct(); //calling ApiController constructor
        $this->middleware('transform.input:' . UserTransformerClass::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        // return response()->json(['data'=>$users],200);
        return $this->showAll($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['admin'] = User::REGULAR_USER;
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);
        // return response()->json(['code'=>201,'data'=>$user],201); //201 response code means record created
        return $this->showOne($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user); //calling ApiResponser
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' .User::ADMIN_USER. ',' .User::REGULAR_USER,
        ]); 

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email !=$request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('only verified users can modify the admin field',409);
            }
            $user->admin = $request->admin; 
        }
        if (!$user->isDirty()) {
            return $this->errorResponse('You need to specify a  different value to update',422);
        }
        
        $user->save();
        // return response()->json(['code'=>200,'data'=>$user],200);
        return $this->showOne($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['code'=>200,'data'=>$user],200);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();
        return $this->showMessage('The account has been verified successfully');

    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified',409);
        }

     
        /**
         * retry function (Laravel Builtin function)
         * Essentially, retry is a general-purpose helper function that helps you attempt the given callback until the given maximum attempt threshold is met.
         * Here’s the signature of this function.
         * function retry($times, callable $callback, $sleep = 0, $when = null);
         * this is used when incase mailing service is down for any reson for some time
         */
        retry(5, function($user){
            Mail::to($user->email)->send(new UserMailChange($user));
        },100);

        return $this->showMessage('The verification email has been resend');
    }

}
