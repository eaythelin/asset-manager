<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NotSameAsOldPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::where("email", request("email"))->first(); 

        //check if the user exists and the new password matches the old hashed password
        //if it fails return the fail message
        if($user && Hash::check($value, $user->password)) {
            $fail("Your new password cannot be the same as your old password.");
        }
    }
}
