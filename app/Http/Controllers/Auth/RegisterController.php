<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Genders;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Base\TemporaryInvitation;
use App\Models\User;
use App\Traits\Helpers\HandlerFilesTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Hash,
    Log,
    Validator,
};
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use HandlerFilesTrait, RegistersUsers {
        register as protected parentRegister;
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 404);

        try {
            $invitation = $this->getInvitationToken($request->query('invitation'));

            return view('auth.register', [
                'storeId' => $invitation->store_id,
                'invitationToken' => $invitation->token,
                'email' => $invitation->email,
            ]);
        } catch (Exception $e) {
            Log::channel('fatal')->error('Could not show registration form | ' . __METHOD__, [
                'Status' => 'Error',
                'Data' => $e->getMessage(),
            ]);

            abort(403, 'You need a valid invitation link');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'invitation' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['prohibited'],
            'phone' => ['required', 'string', 'regex:/^\d{10}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'employee_detail' => ['prohibited'],
            'client_detail' => ['prohibited'],
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
        $invitation = $this->getInvitationToken($data['invitation']);

        if (User::where('email', $invitation->email)->exists()) {
            throw ValidationException::withMessages([
                'email' => ['El usuario con este correo ya existe.'],
            ]);
        }

        if (isset($data['photo']) && $data['photo']->isValid()) {
            $photoPath = $this->upload($data['photo'], 'images');
        }

        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $invitation->email,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'photo' => $photoPath ?? null,
            'status' => 1,
        ]);

        $user->roles()->sync(explode('-', $invitation->roles));

        if ($invitation->store_id) {
            $user->employeeDetail()->create([
                'store_id' => $invitation->store_id,
            ]);
        }

        if (isset($data['client_detail'])) {
            $user->clientDetail()->create([
                'date_of_birth' => $data['client_detail']['date_of_birth'],
                'gender' => $data['client_detail']['gender'],
            ]);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $invitation = $this->getInvitationToken($request->invitation);

        $response = $this->parentRegister($request);

        $invitation->decrement('uses_left', 1);

        if ($invitation->uses_left <= 0)
            $invitation->delete();

        return $response;
    }

    /**
     * Get the invitation token instance from the given request
     * 
     * @return TemporaryInvitation The instance if found
     * 
     * @throws ModelNotFoundException If the invitation token was not found
     * @throws InvalidArgumentException If the invitation token has already been used
     */
    private function getInvitationToken(string $invitationToken)
    {
        $invitation = TemporaryInvitation::where('token', $invitationToken)->firstOrFail();

        if ($invitation->uses_left <= 0) {
            throw new InvalidArgumentException('Invitation link has already been used.');
        } else if (now()->isAfter($invitation->expiration)) {
            throw new InvalidArgumentException('Invitation link has already expired.');
        }

        return $invitation;
    }

    /**
     * Get the dimensions of the image file, the firs element is the width and the second element is the height.
     * 
     * @return array The dimensions of the image
     */
    public function getImageDimensions()
    {
        [900, 900];
    }
}
