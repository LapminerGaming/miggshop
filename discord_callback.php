<?php
session_start();

$client_id = '1257181468006809691';
$client_secret = 'USlotSumg7A9_Du0Re2E2_aoWdb5eTKm';
$redirect_uri = 'YOUR_REDIRECT_URI';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $data = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents('https://discord.com/api/oauth2/token', false, $context);
    $response = json_decode($result, true);

    if (isset($response['access_token'])) {
        $access_token = $response['access_token'];

        // Lấy thông tin người dùng từ Discord
        $user_info = file_get_contents('https://discord.com/api/users/@me', false, stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer $access_token",
            ],
        ]));
        $user_info = json_decode($user_info, true);

        // Lưu thông tin người dùng vào session hoặc database
        $_SESSION['discord_user'] = $user_info;

        // Lưu vào file JSON
        $data = json_decode(file_get_contents('data.json'), true);
        $username = $_SESSION['username'];
        $data['users'][$username]['discord'] = $user_info;
        file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));

        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error: Unable to get access token";
    }
} else {
    echo "Error: Authorization code not found";
}
?>
