<?php

  

namespace App\Service;

  

require_once __DIR__ . '/../../vendor/autoload.php';

  

use Google\Service\Oauth2;

use App\Interface\AuthInterface;

  
  

class GoogleAuthService implements AuthInterface {

  
  

public function authenticate($data): bool {

$env = parse_ini_file('.env');

$google_oauth_client_id = $env["CLIENT_ID"];

$google_oauth_client_secret = $env["CLIENT_SECRET"];

$google_oauth_redirect_uri = 'http://localhost:8080/stupid-blog-overkill/auth/google/callback';

  

$client = new \Google_Client();

$client->setClientId($google_oauth_client_id);

$client->setClientSecret($google_oauth_client_secret);

$client->setRedirectUri($google_oauth_redirect_uri);

$client->addScope("https://www.googleapis.com/auth/userinfo.email");

$client->addScope("https://www.googleapis.com/auth/userinfo.profile");

  

if (isset($_GET['code']) && !empty($_GET['code'])) {

  

$accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    $client->setAccessToken($accessToken);

  

    if (isset($accessToken['access_token']) && !empty($accessToken['access_token'])) {

  

      $google_oauth = new Oauth2($client);

      $google_account_info = $google_oauth->userinfo->get();

  

      if (isset($google_account_info->email)) {

  

        session_regenerate_id();

        $_SESSION['google_loggedin'] = TRUE;

        $_SESSION['google_email'] = $google_account_info->email;

        $_SESSION['google_name'] = $google_account_info->name;

        $_SESSION['google_picture'] = $google_account_info->picture;

  

        header('Location: /stupid-blog-overkill/profile');

        exit;

      } else {

        exit('Could not retrieve profile information! Please try again later!');

      }

    } else {

      exit('Invalid access token! Please try again later!');

    }

    } else {

  

      $authUrl = $client->createAuthUrl();

  

      header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));

      exit;

    }

  }

  

  public function handleGoogleCallback($data) {

  

    $env = parse_ini_file('.env');

    $google_oauth_client_id = $env["CLIENT_ID"];

    $google_oauth_client_secret = $env["CLIENT_SECRET"];

    $google_oauth_redirect_uri = 'http://localhost:8080/stupid-blog-overkill/auth/google/callback';

    $client = new \Google_Client();

    $client->setClientId($google_oauth_client_id);

    $client->setClientSecret($google_oauth_client_secret);

    $client->setRedirectUri($google_oauth_redirect_uri);

    $client->addScope("https://www.googleapis.com/auth/userinfo.email");

    $client->addScope("https://www.googleapis.com/auth/userinfo.profile");

    $code = $data['code'] ?? null;

    if ($code) {

  

      $accessToken = $client->fetchAccessTokenWithAuthCode($code);

      $client->setAccessToken($accessToken);

  

      $oauth2 = new \Google\Service\Oauth2($client);

      $userInfo = $oauth2->userinfo->get();

      $_SESSION['user'] = [

        'name' => $userInfo->name,

        'email' => $userInfo->email,

        'google_loggedin' => true,

        'auth_type' => 'google',

        'role' => 'ROLE_GOOGLE'

      ];

      header('Location: /stupid-blog-overkill');

      exit;

    } else {

      exit('Erreur d\'authentification Google.');

    }

  }

  
  

}