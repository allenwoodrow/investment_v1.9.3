<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;
use App\Models\Profile;
use App\Models\Account;
use App\Services\JWTService;
use App\Models\PasswordReset;
use App\Notifications\ForgotPassword;
use App\Utils\Meta;
use App\Notifications\EmailVerify;
use App\Models\UserVerify;
use App\Events\AccountRegistered;
use App\Events\EmailVerified;

class AuthController extends Controller
{
    
 public function register(Request $request) {
        $form = Validator::make($request->all(), [
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'country' => ['required', 'alpha'],
            'phone' => ['required', 'min:9'],
            'fullname' => ['required', 'string'],
            'dob' => ['required', 'date'],
            'zip' => ['nullable'],  // Made optional
        ]);
        
        if ($form->fails()) {
            // Return all validation errors for debugging
            $errors = $form->errors()->all();
            return $this->failed(implode(', ', $errors));
        }
        
        $params = $form->validated();
        
        // Check for existing user manually for better error message
        $existingUsername = User::where('username', $params['username'])->first();
        if ($existingUsername) {
            return $this->failed('Este nome de usuário já está em uso.');
        }
        
        $existingEmail = User::where('email', $params['email'])->first();
        if ($existingEmail) {
            return $this->failed('Este email já está registrado.');
        }
        
        // Create user with HASHED password
        $user = User::create([
            "username" => $params["username"],
            "email" => $params["email"],
            "password" => Hash::make($params['password']), // HASHED!
            'active' => true,
            'email_verified_at' => Carbon::now()
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'fullname' => $params['fullname'], 
                'phone' => $params['phone'],
                'zip' => $params['zip'] ?? '',  // Use empty string if not provided
                'dob' => Carbon::parse($params['dob']),
                'country' => $params['country']
            ]
        );
        
        $role = Role::findByName('client');
        $user->assignRole($role);

        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'wallet'
        ]);

        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'trading'
        ]);
        
        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'commission'
        ]);

        // Skip email verification flow: do not fire AccountRegistered event

        return $this->success([
            'message' => 'Registro bem-sucedido!'
        ]);
    }

    public function login(Request $request) {
        $input = $request->only('email', 'password');
        $form = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if($form->fails()) {
            return $this->failed($form->getMessageBag()->first());
        }

        $credentials = $form->validated();

        $user = User::where('email', $credentials['email'])->first();
        
        if(!$user) {
            return $this->failed('Email ou senha incorretos.');
        }

        if(!$user->active) {
            return $this->failed('Conta inativa. Verifique seu email.');
        }

        // Use Hash::check for hashed passwords
        if(!Hash::check($input['password'], $user->password)) {
            return $this->failed('Email ou senha incorretos.');
        }
        
        // Manually login the user
        Auth::login($user);
        
        $authHandler = new JWTService;
        $token = $authHandler->GenerateToken($user);
        
        return $this->success([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function refresh(Request $request) {
        $authHandler = new JWTService;
        try {
            $token = $authHandler->refreshToken();
            if(!$token) {
                return $this->failed('Token refresh failed');
            }
            return $this->success([ 'token' => $token]);

        } catch (Exception $e){
           return $this->failed('Token refresh failed');
        }
    }

    public function revoke_token(Request $request) {
        // $authHandler = new JWTService;
        // $authHandler->revokeToken();
        Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return $this->success([
            'message' => 'Logged out'
        ]);
    }

    public function forgot_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email']
        ]);
    
        if ($validator->fails()) {
            return $this->failed($validator->errors()->first());
        }
    
        $valid = $validator->validated();
    
        $user = User::where('email', $valid['email'])->first();
    
        if (!$user) {
            return $this->failed('User not found');
        }
    
        $token = Str::random(64);
    
        PasswordReset::updateOrCreate(
            ['email' => $valid['email']],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );
    
        try {
            $user->notify(new ForgotPassword($token, $user->username, $user->email));
        } catch (\Throwable $e) {
            \Log::error('Password reset email failed: ' . $e->getMessage());
            return $this->failed('Password reset email failed. Please check mail settings.');
        }
    
        return $this->success('Password reset request was successful, please check your email');
    }

    public function showResetForm(Request $request) {
        $email = $request->route('email');
        $token = $request->route('token');
        if(!$email || !$token) {
            return redirect()->route('web.reset_failed');
        }

        $resetter = \App\Models\PasswordReset::where('email', $email)
        ->where('token', $token)->first();

        if($resetter == NULL) {
            return redirect()->route('web.reset_failed');
        }

        return $this->showVueRoute();
    }

    public function reset_password(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8'],
        ]);
        if($validator->fails()) {
            return $this->failed('Please check the password entered');
        } else {
            $valid = $validator->validated();
            $email = $valid['email'];
            $token = PasswordReset::where('email', $email)
            ->where('token', $valid['token'])
            ->first();

            if ($token) {
                $user = User::where('email', $valid['email'])->first();
                $user->password = Hash::make($valid['password']);
                $user->save();
                $token->delete();
                return $this->success('Password reset successfully');
            } else {
                return $this->failed('Unable to validate token');
            }
        }
    }

    function showVerification(Request $request) {
        return $this->showVueRoute();
    }

    function resend_verification(Request $request) {
        $email = $request->route('email');
        if(!$email) {
            return redirect('/not-found');
        }
        $user = User::where('email', $email)->first();

        if(!$user || $user->id == NULL) {
            return redirect('/not-found');
        }
        if($user->email_verified_at == NULL) {
            $record = UserVerify::where('user_id', $user->id)->first();
            if($record->id == NULL){
                return redirect('/not-found');
            }

            $token = Str::random(64);
            $url = route('verification.verify', ['id' => $user->id, 'hash' => $token]);
            $record->token = $token;
            $record->save();

            $notification = new EmailVerify($user->username, $url);
            $user->notify($notification);
        } else {
            return redirect('/auth/verification-successful', 301);
        }
        return redirect()->route('verification.notice', ['email' => $email]);
    }

    function verify(Request $request) {
        $id = $request->route('id');
        $hash = $request->route('hash');
        
        if(!$id || !$hash) {
            return redirect('/auth/verification-failed');
        }
        
        $user = User::where('id', $id)->first();
        $model = UserVerify::where('user_id', $id)->where('token', $hash)->first();
        
        if($user == NULL || $model == NULL) {
            return redirect('/auth/verification-failed');
        }
        
        if ($user->markEmailAsVerified()) {
            $user->active = true;
            $user->save();
            $model->delete();
            event(new EmailVerified($user));
            return redirect('/auth/verification-success');
        } else {
            return redirect('/auth/verification-failed');
        }
    }

    function verification_success(Request $request) {
        return $this->showVueRoute();
    }

    function verification_failed(Request $request) {
        return $this->showVueRoute();
    }
}
