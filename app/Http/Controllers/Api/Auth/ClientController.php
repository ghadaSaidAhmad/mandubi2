<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client as User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\ClientLoginRequest;
use App\Http\Requests\Auth\ClientRegisterRequest;
use App\Http\Requests\Auth\ClientCompleteRegisterRequest;
use File;


class ClientController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'completeRegister','verifyMobile']]);
    }

    /**
     * set mandub active and update his location
     * @return \Illuminate\Http\Response
     */

    public function verifyMobile(Request $request)
    {
        //set mandub active and update his location
        try {
            $user = User::find($request->client_id);
            $user->update([
                'phone_verified_at' => $request->phone_verified_at,
                'verification_code' => $request->verification_code
            ]);
            $this->initResponse('success', $user, 200, 'data');
        } catch (Exception $e) {
            $this->initResponse('faild', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }
    function login(ClientLoginRequest $request)
    {
        //user not found
        $credentials = request(['phone', 'password']);
        if (!$token = auth('clients')->attempt($credentials)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }
        $user = auth('clients')->user();

        //create token
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
    public function register(ClientRegisterRequest $request)
    {
        try {
            // create user request 
            $user = $request->only([
                'phone',
                'gender',
                'rpassword',
                'password',
                'email'
            ]);
            $user["password"] = Hash::make($user["password"]);
            $user = User::create($user);
            //TODO send sms to verify mobile number 

            $this->initResponse('', $user, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
    }

    /**
     * user  complete registration
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @return Response
     */
    public function completeRegister(ClientCompleteRegisterRequest $request)
    {
        try {
            $user = User::where('phone', $request->phone)->first();
            if (!$user) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
            if ($request->hasFile('profile_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->profile_image->getClientOriginalName();

                $user->update(['profile_image' => $imageName]);

                $request->profile_image->move($destination, $imageName);
            }
            //upload national_id_front_image if exist
            if ($request->hasFile('national_id_front_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_front_image->getClientOriginalName();

                $user->update(['national_id_front_image' => $imageName]);

                $request->national_id_front_image->move($destination, $imageName);
            }
            //upload national_id_back_image if exist
            if ($request->hasFile('national_id_back_image')) {
                $destination = public_path() . '/images/clients';  // upload images in public path images/events

                File::isDirectory($destination) or File::makeDirectory($destination, 0777, true, true);  // make sure folder exists.

                $imageName = microtime(time()) . "_" . $request->national_id_back_image->getClientOriginalName();

                $user->update(['national_id_back_image' => $imageName]);

                $request->national_id_back_image->move($destination, $imageName);
            }
            //set complete register flag true if user updated
            $user->update(['complete_register' => 1]);
            //TODO send sms to verify mobile number

            $this->initResponse('', $user, 200, 'data');

        } catch (Exception $e) {
            $this->initResponse('', $e->getMessage(), 400, 'error');
        }
        return response()->json($this->response, $this->code);
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
