<?php


namespace App\Http\Controllers\Api\V1;


use App\EloquentQueries\Api\Interfaces\UserQueries;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserPhotoDeleteRequest;
use App\Http\Requests\UserVipRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\interfaces\IUserService;
use App\Models\UserPhoto;

class UserController extends Controller
{
    private $userService;
    private $userQueries;

    public function __construct(IUserService $userService, UserQueries $userQueries)
    {
        $this->userService = $userService;
        $this->userQueries = $userQueries;
    }

    public function user()
    {

    return (new UserResource(
        $this->userService->getUser(auth()->id())
    ))->response()
        ->setStatusCode(200);
    }

    public function update(UpdateUserRequest $updateUserRequest)
    {
        $this->userService->update(auth()->id(), $updateUserRequest->all());
        return response()->json([
            'message' => "successfully updated"
        ], 202);
    }

    public function destroy(UserPhotoDeleteRequest $request)
    {
      //
    }

    public function markAsVip(UserVipRequest $userVipRequest)
    {
        $this->userService->markAsVip(auth()->id());
        return response()->json([
            'message' => "VIP status successfully updated"
        ], 202);
    }

    public function checkVip(UserVipRequest $userVipRequest)
    {
        return response()->json([
            'success' => $this->userQueries->checkVip(auth()->id())
        ], 200);

    }

}



















