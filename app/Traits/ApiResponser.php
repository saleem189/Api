<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Fractalistic\Fractal;

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
    private function errorResponse($message, $code = 422){ //by default 422 response ( Unprocessable Entity )
        return response()->json(['error'=>$message, 'code' => $code], $code);
    }

    /**
     * to return all the records in a collection .. eg(User::all())
     * @return collectionAll example User::all()->get() into collections and return in response
     */
    private function showAll(Collection $collection, $code = 200){
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code); 
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->sortData($collection, $transformer);
        $collection =  $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    /**
     * return single record of model in response
     * @return singleRecord  example  User::find()->first()
     */
    private function showOne(Model $model, $code = 200){
        $transformer = $model->transformer; //every modal has public property of transformer defined in
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $code);
    }

    /**
     * return message for user 
     * @return messageResponse we used it in mails and other simple messages returning
     */
    private function showMessage($message, $code = 200){
        return $this->successResponse(['data' => $message, 'code' => $code], $code);
    }

    /**
     * sorting user by name or any other dynamic attribute passed in url with parameter sort_by  eg url?sort_by=name
     * if no attribute is pass 
     */
    public function sortData(Collection $collection ,$transformer){
        if(request()->has('sort_by')){
            $attribute = $transformer::orignalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};  //here we are using Higher Order Messages 
        }
        return $collection;
    }


    /**
     * @param $data @param $transformer
     * getting data and transformering its data into array
     */
    protected function transformData($data, $transformer){
        $transaformation = fractal($data, new $transformer);
        return $transaformation->toArray();
    }

}