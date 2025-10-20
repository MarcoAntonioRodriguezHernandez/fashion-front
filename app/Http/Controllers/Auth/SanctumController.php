<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Helpers\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Validator,
};
use Symfony\Component\HttpFoundation\Response;

class SanctumController extends Controller
{
    use ResponseTrait;

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validate = $this->registerValidator($request->all());

        if ($validate->fails()) {
            return $this->makeResponse([
                'success' => false,
                'message' => 'Validation Error',
                'status' => Response::HTTP_BAD_REQUEST,
                'data' => $validate->errors(),
            ]);
        }

        $user = $this->create($request->all());

        return $this->makeResponse([
            'success' => true,
            'message' => 'User has been created successfully',
            'status' => Response::HTTP_CREATED,
            'data' => [
                'token' => $user->createToken($request->email)->plainTextToken,
                'user' => $user,
            ],
        ]);
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate = $this->loginValidator($request->all());

        if ($validate->fails()) {
            return $this->makeResponse([
                'success' => false,
                'message' => 'Validation Error',
                'status' => Response::HTTP_BAD_REQUEST,
                'data' => $validate->errors(),
            ]);
        }

        // Check email exist
        $user = User::where('email', $request->email)->first();

        // Check password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->makeResponse([
                'success' => false,
                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ]);
        }

        return $this->makeResponse([
            'success' => true,
            'message' => 'User has logged in successfully',
            'status' => Response::HTTP_OK,
            'data' => [
                'token' => $user->createToken($request->email)->plainTextToken,
                'user' => $user,
            ],
        ]);
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->makeResponse([
            'success' => true,
            'message' => 'User has logged out successfully',
            'status' => Response::HTTP_OK,
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function registerValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/\d{10}/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['prohibited'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'status' => true,
        ]);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function loginValidator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);
    }
}
