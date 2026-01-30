<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use App\Rules\NotSameAsOldPassword;

class ResetPasswordController extends Controller
{
    //
    public function getResetPage(Request $request, string $token){
        //go to form view with the password reset token
        return view('auth.resetpassword',
        ["token" => $token,
               "email" => $request->email]);
    }

    public function resetPassword(Request $request){
        $validated = $request->validate([
            "token" => ["required"],
            "email" => ["required", "email", "exists:users,email", "max:255", "string"],
            "password" => ["required", "min:8", "string", "confirmed", new NotSameAsOldPassword]
        ]);

        $status = Password::reset($validated, 
            function(User $user, string $password){
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            });

        return $status === Password::PasswordReset
            ? redirect() -> route("login")-> with("success", __($status))
            : back() -> withErrors(["email" => __($status)]);
    }
}
