<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Illuminate\Support\Facades\Session;

class DemoController extends Controller
{
    public function index(Request $request)
    {

        $redirectUrl = 'https://auto-ecole.localhost';

        # Create and configure client
        $client = new Client();
        $client->setAuthConfig(base_path('youtube.json'));
        $client->setRedirectUri($redirectUrl);
        $client->addScope('https://www.googleapis.com/auth/youtube');

        $connected = false;

        # === SCENARIO 1: PREPARE FOR AUTHORIZATION ===
        if (!$request->has('code') && !Session::has('google_oauth_token')) {
            Session::put('code_verifier', $client->getOAuth2Service()->generateCodeVerifier());

            # Get the URL to Google’s OAuth server to initiate the authentication and authorization process
            $authUrl = $client->createAuthUrl();

            return view('demo')->with(['connected' => $connected, 'authUrl' => $authUrl]);
        }

        # === SCENARIO 2: COMPLETE AUTHORIZATION ===
        if ($request->has('code')) {
            # Exchange the authorization code for an access token
            $token = $client->fetchAccessTokenWithAuthCode($request->input('code'), Session::get('code_verifier'));
            $client->setAccessToken($token);
            Session::put('google_oauth_token', json_encode($token));

            return redirect($redirectUrl);
        }

        # === SCENARIO 3: ALREADY AUTHORIZED ===
        if (Session::has('google_oauth_token')) {
            $token = json_decode(Session::get('google_oauth_token'), true);
            $client->setAccessToken($token);
            if ($client->isAccessTokenExpired()) {
                Session::forget('google_oauth_token');
            } else {
                $connected = true;
            }
        }

        # === SCENARIO 4: TERMINATE AUTHORIZATION ===
        if ($request->has('disconnect')) {
            Session::forget(['google_oauth_token', 'code_verifier']);
            return redirect($redirectUrl);
        }

        return view('demo')->with(['connected' => $connected]);
    }
}
