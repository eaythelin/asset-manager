<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    //
    public function getForgotPage(){
        return view("auth.forgotpassword");
    }

    public function sendResetLink(Request $request){
        //first validate then send password reset link through email
        $request->validate([
            "email"=>["required", "email", "exists:users,email", "max:255"]
        ]);

        $status = Password::sendResetLink($request->only("email"));

        return $status === Password::ResetLinkSent
        ? back()->with(['status' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }
}
