<?php


namespace App\Http\Controllers\Api\V1;


use App\EloquentQueries\Api\Interfaces\InterestedFilterQueries;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInterestedFilterRequest;

class InterestedFilterController extends Controller
{

    private $interestedFilterQueries;

    public function __construct(InterestedFilterQueries $interestedFilterQueries)
    {
        $this->interestedFilterQueries = $interestedFilterQueries;
    }


    public function update(UpdateInterestedFilterRequest $request)
    {
        $this->interestedFilterQueries->update(auth()->id(),$request->all());

        return response()->json(['message' => 'successfully updated'], 202);

    }


}