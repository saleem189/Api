<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
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

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginator($collection);
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
     * @return collection
     */
    public function sortData(Collection $collection ,$transformer){
        if(request()->has('sort_by')){
            $attribute = $transformer::orignalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};  //here we are using Higher Order Messages 
        }
        return $collection;
    }


    /**
     * @param data 
     * @param transformer
     * getting data and transformering its data into array
     */
    protected function transformData($data, $transformer){
        $transaformation = fractal($data, new $transformer);
        return $transaformation->toArray();
    }

    /**
     * filteriing data by the attributes values and names
     * @param query,Collections,Transformer
     * @return filteredData 
     */
    protected function filterData(Collection $collection, $transformer){
        foreach(request()->query() as $query => $value){
            $attribute = $transformer::orignalAttributes($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection; 
    }

    /**
     * Using custom Paginator
     * @param Collection
     * @return paginator
     */
    protected function paginator(Collection $collection){

        // validating rules for custom pages
        $rules = [
            'per_page' => 'integer|min:2|max:50',
        ];
        // using validator facade for independent working of validating rules and validation
        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage(); //getting current page
        $perPage = 15;   //elements in per page

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;   // implecetly defining interger value
        }

        $result = $collection->slice(($page-1)*$perPage,$perPage)->values();              //slice method will receive form which elemnt we are going to slice and quantity of elements of the page
        $paginator = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPage(), //getting current page
        ]);  //making paginator instance that receives the results,  real size of sollections, quantity of elements per page, current page and options in array

        //to include other requests parameters we will append paginator to include them eg sorting request query and other filters
        // $paginator->withPath(url()->current())->appends(request()->all()); //custom defining and appending paths and url

        $paginator->withQueryString()->withPath(url()->current());  //laravel function to include querystring by default in link
        return $paginator;
    }

}