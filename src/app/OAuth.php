<?php

// use GuzzleHttp\Client;
// use GuzzleHttp\Psr7\Request;
use League\OAuth2\Client\Provider\Google;
// $provider = new \League\OAuth2\Client\Provider\Github(config('oauth.provider.github'));
$provider = new Google(config('oauth.provider.google'));
// Redirect the user to the Google login page
if (!isset($_GET['code'])) {
    // Generate the authorization URL
    $authUrl = $provider->getAuthorizationUrl();

    // Store the state in the session for security validation
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL
    header('Location: ' . $authUrl);
    exit;
} else {
    // Validate the state to prevent CSRF attacks
    if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    }

    // Exchange the authorization code for an access token
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);

    // Get the user's details
    $user = $provider->getResourceOwner($token);

    // Access user details
    // $userId = $user->getId();
    // $userName = $user->getNickname();
    // $userEmail = $user->getEmail();

    // Perform actions with user details as needed
    // ...

    // Clear the session state
    unset($_SESSION['oauth2state']);
    // Redirect to a success page
    // header('Location: /success.php');
    // exit;

// if (!isset($_GET['code'])) {

//     // If we don't have an authorization code then get one
//     $authUrl = $provider->getAuthorizationUrl();
//     $_SESSION['oauth2state'] = $provider->getState();
//     header('Location: '.$authUrl);
//     exit;

// // Check given state against previously stored one to mitigate CSRF attack
// } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

//     unset($_SESSION['oauth2state']);
//     exit('Invalid state');

// } else {
//     // Try to get an access token (using the authorization code grant)
//     $token = $provider->getAccessToken('authorization_code', [
//         'code' => $_GET['code'],
//         // 'client_id'=> '1d9d1f55b5288b46dc97',
//         // 'client_secret'=>'611ff99b8d193a4416bf80062b54851635fc330e'
//     ]);
//     // Optional: Now you have a token you can look up a users profile data
//     // $client = new Client();
//     // $headers = [
//     // 'Authorization' => 'Bearer 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'
//     // ];
//     // $request = new Request('GET', 'https://api.github.com/user', $headers);
//     // $res = $client->sendAsync($request)->wait();
//     // echo $res->getBody();
//     try {

//         $user = $provider->getResourceOwner($token);
//         $user2 = $user->toArray();
//         print_r('uhty '.$user2);
//         // Use these details to create a new profile
//         // printf('Hello %s!', $user->getNickname());

//     } catch (Exception $e) {

//         // Failed to get user details
//         logger('errot', json_encode($e));
//         exit('Oh dear...');
//     }

    // Use this to interact with an API on the users behalf
    // echo $token->getToken();
}