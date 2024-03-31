<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class AuthServices
{
    protected $user;
    protected $authDriver = "github";

    public function handleAuthCallback()
    {
        try {

            //get the user from the github after login success from the github
            $user = Socialite::driver($this->authDriver)->user();
            //check if the logged in user is already in our system or not
            $existingUser = User::where('github_id', $user->id)->first();

            //if user is already in our system login the user
            if ($existingUser) {
                $this->handleLogin($existingUser);
                return true;
            } else {
                //if the user is not in our system create the user
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => $user->name,
                    'github_id' => $user->id,
                    'password' => encrypt('testPw')
                ]);
                //login the user after creating the user
                $this->handleLogin($newUser);
                return true;
            }
        } catch (Exception $e) {
            //print the exception to forntend if any error accourse during the authentication
            dd($e->getMessage());
            return false;
        }
    }
    private function handleLogin($newUser)
    {
        //for authenticating the user to our app
        Auth::login($newUser);
    }
}
