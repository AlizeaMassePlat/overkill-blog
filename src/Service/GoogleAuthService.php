<?php

namespace App\Service;

require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Service\Oauth2;
use App\Interface\AuthInterface;


class GoogleAuthService implements AuthInterface {


    public function authenticate($data): bool {
     // Update the following variables
    $google_oauth_client_id = '922414642982-cfo5gemgqucqqa24klddl79dpi8adnqd.apps.googleusercontent.com';
    $google_oauth_client_secret = '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';
    $google_oauth_redirect_uri = 'http://localhost:8080/stupid-blog-overkill/auth/google/callback';
    $google_oauth_version = 'v3';
    // Create the Google Client object
    $client = new \Google_Client();
    $client->setClientId($google_oauth_client_id);
    $client->setClientSecret($google_oauth_client_secret);
    $client->setRedirectUri($google_oauth_redirect_uri);
    $client->addScope("https://www.googleapis.com/auth/userinfo.email");
    $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
    // If the captured code param exists and is valid
    if (isset($_GET['code']) && !empty($_GET['code'])) {
        // Exchange the one-time authorization code for an access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($accessToken);
        // Make sure access token is valid
        if (isset($accessToken['access_token']) && !empty($accessToken['access_token'])) {
            // Now that we have an access token, we can fetch the user's profile data
            $google_oauth = new Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            // Make sure the profile data exists
            if (isset($google_account_info->email)) {
                // Authenticate the user
                session_regenerate_id();
                $_SESSION['google_loggedin'] = TRUE;
                $_SESSION['google_email'] = $google_account_info->email;
                $_SESSION['google_name'] = $google_account_info->name;
                $_SESSION['google_picture'] = $google_account_info->picture;
                // Redirect to profile page
                header('Location: /stupid-blog-overkill/profile');
                exit;
            } else {
                exit('Could not retrieve profile information! Please try again later!');
            }
        } else {
            exit('Invalid access token! Please try again later!');
        }
        } else {
            // Redirect to Google Authentication page
            $authUrl = $client->createAuthUrl();
            // var_dump($authUrl);die;
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit;
        }
    }

    public function handleGoogleCallback($data) {

        $google_oauth_client_id = '922414642982-cfo5gemgqucqqa24klddl79dpi8adnqd.apps.googleusercontent.com';
        $google_oauth_client_secret = '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';
        $google_oauth_redirect_uri = 'http://localhost:8080/stupid-blog-overkill/auth/google/callback';
        $client = new \Google_Client();
        $client->setClientId($google_oauth_client_id);
        $client->setClientSecret($google_oauth_client_secret);
        $client->setRedirectUri($google_oauth_redirect_uri);
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $code = $data['code'] ?? null;
        
        if ($code) {
            // Échangez le code contre un token d'accès
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($accessToken);
    
            // Obtenez des informations sur l'utilisateur
            $oauth2 = new \Google\Service\Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            
            // Stockez les informations nécessaires dans la session ou traitez-les comme vous le souhaitez
            $_SESSION['user'] = [
                'name' => $userInfo->name,
                'email' => $userInfo->email,
                'google_loggedin' => true,
                'auth_type' => 'google',
                'role' => 'ROLE_GOOGLE'
                // Ajoutez d'autres informations que vous souhaitez stocker
            ];
            // Redirigez vers une page où vous affichez le message de bienvenue
            header('Location: /stupid-blog-overkill');
            exit;
        } else {
            // Gérez l'erreur ou le cas où le code n'est pas présent
            exit('Erreur d\'authentification Google.');
        }
    }


}