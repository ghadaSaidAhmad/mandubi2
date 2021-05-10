<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mandub as User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\MandoubLoginRequest;
use App\Http\Requests\Auth\MandoubRegisterRequest;
use Intervention\Image\Facades\Image;
use File;

class MandubController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * user login
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @return Response
     */
    function login(MandoubLoginRequest $request)
    {
        //user not found
        $credentials = request(['phone', 'password']);
        if (!$token = auth('mandubs')->attempt($credentials)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }
        //check verified user
        $user = auth('mandubs')->user();
        $verified = $this->checkUser($user);

        if ($verified['code'] != '200') {
            return response([
                'message' => [$verified['message']]
            ], $verified['code']);
        }
    
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * user registration
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @return Response
     */
    public function register(MandoubRegisterRequest $request)
    {

        try {
            $user["password"] = Hash::make($request["password"]);
            $user = User::create($request->except(['token']));
            //upload national_id_front_image if exist
            if ($request->hasFile('profile_image')) {
                $destination = public_path() . '/images/mandoubs';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->profile_image->getClientOriginalName();

                $user->update(['profile_image' => $imageName]);

                $request->profile_image->move($destination, $imageName);
            }

            //upload national_id_front_image if exist
            if ($request->hasFile('national_id_front_image')) {
                $destination = public_path() . '/images/mandoubs';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_front_image->getClientOriginalName();

                $user->update(['national_id_front_image' => $imageName]);

                $request->national_id_front_image->move($destination, $imageName);
            }
            //upload national_id_back_image if exist
            if ($request->hasFile('national_id_back_image')) {
                $destination = public_path() . '/images/mandoubs';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_back_image->getClientOriginalName();

                $user->update(['national_id_back_image' => $imageName]);

                $request->national_id_back_image->move($destination, $imageName);
            }

            //TODO send sms to verify mobile number 


            $this->initResponse('', $user, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * checkUser check if user is verified
     * @param $user
     * @return Response
     */

    public function checkUser($user)
    {

        //check mobile verification
        if (!$user->verification_code) {
            return [
                'message' => 'mobile number not verified',
                'code' => 403
            ];
        }
        // TODO check complete register
        if (!$user->complete_register) {
            return [
                'message' => 'please complete register',
                'code' => 403
            ];
        }
        // TODO check admin agree 
        if (!$user->admin_agree) {
            return [
                'message' => 'The account is under review',
                'code' => 403
            ];
        }
        //user is verified
        return [
            'message' => 'verified user',
            'code' => 200
        ];
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 0
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
