<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{

    /**
     * defining success response function to return a successful response
     * @return successData
     */
    private function successResponse($data, $code =200){  //by default 200 response
        return response()->json($data,$code);
    }

    /**
     * defing Error response to return error message
     * @return errorMessage
     */
    private function errorResponse($message, $code){
        return response()->json(['error'=>$message, 'code' => $code], $code);
    }

    /**
     * to return all the records in a collection .. eg(User::all())
     * @return collectionAll example User::all()->get() into collections and return in response
     */
    private function showAll(Collection $collection, $code = 200){
        return $this->successResponse(['data' => $collection, 'code' => $code], $code);
    }

    /**
     * return single record of model in response
     * @return singleRecord  example  User::find()->first()
     */
    private function showOne(Model $model, $code = 200){
        return $this->successResponse(['data' => $model, 'code' => $code], $code);
    }

       /**
     * return single record of model in response
     * @return singleRecord  example  User::find()->first()
     */
    private function showMessage($message, $code = 200){
        return $this->successResponse(['data' => $message, 'code' => $code], $code);
    }

}