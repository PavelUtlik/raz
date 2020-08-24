<?php


namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\File;
use App\EloquentQueries\Api;
use App\EloquentQueries\Api\EloquentUserPhotoQueries;
use App\EloquentQueries\Api\Interfaces\UserPhotoQueries;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingDeleteRequest;
use App\Http\Requests\UpdateUserPhotoRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserPhotoDeleteRequest;
use App\Http\Requests\UserPhotosRequest;
use App\Http\Resources\UserPhotoResource;
use App\Http\Resources\UserPhotosResource;
use App\Http\Resources\UserResource;
use App\Http\Services\interfaces\IRegisterUserService;
use App\Http\Services\interfaces\IUserPhotoService;
use App\Http\Services\interfaces\IUserService;
use App\Models\UserPhoto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

class UserPhotoController extends Controller
{

    private $userPhotoService;
    private $userService;
    private $userPhotoQueries;

    public function __construct(IUserPhotoService $userPhotoService, IUserService $userService, UserPhotoQueries $userPhotoQueries)
    {
        $this->userPhotoService = $userPhotoService;
        $this->userService = $userService;
        $this->userPhotoQueries = $userPhotoQueries;
    }

    public function add(UserPhotosRequest $request)
    {


        $data = $request->all();

        $this->userPhotoService->addPhoto($data);

        return response()->json([
            'message' => 'photo successfully added!'
        ], 201);
    }

    /**
     * todo: old
     * @param UserPhotosRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMany(UserPhotosRequest $request)
    {
        $data = $request->all();
        $this->userPhotoService->addPhotos($data);
        return response()->json([
            'message' => 'photos successfully added!'
        ], 201);
    }

    public function makeMain(UpdateUserPhotoRequest $request)
    {

        $data = $request->all();
        $this->userPhotoService->updatePhoto(auth()->id(), $data);
        return response()->json([
            'message' => 'photos successfully updated!'
        ], 201);

    }

    public function checkIsMain($request)
    {

        return response()->json([
            'message' => $this->userPhotoQueries->checkIsMain($request)
        ], 200);
    }


//    public function getUserPhotos($request){
//
//
//        $userId = $request;
//        dd( $this->userPhotoService->get($userId));
//        return response()->json([
//            'photo' => $this->userPhotoService->get($userId),
//        ], 200);
//    }

    public function getByUserId($userId)
    {

        return UserPhotoResource::collection($this->userPhotoQueries->getByUserId(
            $userId
        ))
            ->response()
            ->setStatusCode(200);
    }


    public function destroy(UserPhotoDeleteRequest $request)
    {

        $id = $request->get('photo_id');
        $this->userPhotoService->delete($id);

        return response()->json([
            'message' => "photo successfully deleted"
        ], 202);
    }


}