<?php

$url = 'https://token.vudrm.tech/v2/generate';
//SAMPLE POLICY with geo_restrictions and block_vpn set
$policy = '{ "clientName": "REPLACE_WITH_CLIENTNAME", "policy": {"rental_duration_seconds":"3600"}, "geo_restrictions": {"country_code_whitelist":["dom"], "block_vpn_and_tor":true}}';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $policy);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key: REPLACE_WITH_API_KEY', 'Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$response_token = json_decode($result);
$token = $response_token->{'token'};
echo $token;
$response['drm'] = $token;
$response['content'] = $content;

echo json_encode($response);

?>
