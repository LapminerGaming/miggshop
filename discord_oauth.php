<?php
session_start();

$client_id = '1257181468006809691';
$redirect_uri = 'USlotSumg7A9_Du0Re2E2_aoWdb5eTKm';
$scope = 'identify email';

$url = 'https://discord.com/api/oauth2/authorize?client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code&scope=' . urlencode($scope);
header('Location: ' . $url);
exit;
?>
