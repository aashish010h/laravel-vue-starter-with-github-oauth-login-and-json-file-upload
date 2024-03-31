<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use App\Services\Auth\AuthServices;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    // declaration of variable for the dependency injection of auth service for github oauth
    protected $authService;


    // constructor function for injecting the auth service instance
    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    //redirect to the github  authentication page for login with o-auth
    public function redirectToGithub()
    {
        // provided by sociallite for redirect for github
        return Socialite::driver('github')->redirect();
    }


    // handle GitHub callback after authentication
    public function handleGithubCallback()
    {
        // Call the handleAuthCallback method from AuthServices to process the GitHub callback
        $result =  $this->authService->handleAuthCallback();

        // redirect user to dashboard page after successful authentication
        if ($result) return redirect()->intended('dashboard');
    }


    // generate a personal access token for the authenticated user
    public function generateToken()
    {

        $user = Auth::user();

        // create a personal access token for the user for using in vue frontend for api calls using sanctum
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // return the token as JSON response
        return response()->json(["token" => $token]);
    }

    public function logout()
    {
        //get the current authenticated user
        $user = Auth::user();
        $user->tokens()->delete(); // Revoke all personal access tokens associated with the user

        // logout for  current session so that vue and laravel user with same session gets logged out
        Auth::guard('web')->logout();
        return response()->json(["status" => "200", "message" => $user]);
    }
}
