<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\DeviceTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailAndPasswordValidateRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->userService->register($request->all());
    }

    public function validateEmailAndPassword(EmailAndPasswordValidateRequest $request)
    {
        return response()->json([
            'message' => 'Successfully check',
        ],200);
    }

    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->device_id = 'vffvg';
        $token->device_type = DeviceTypes::IOS;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(4);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }


    public function loginFromGoogle()
    {

    }


    public function loginFromFacebook()
    {

    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


}

















