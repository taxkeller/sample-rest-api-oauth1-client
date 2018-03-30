<?php
// Http method
$method = 'POST';

// URL of getting token
$url    = 'https://(your domain)/oauth1/access';    // fixme
$encoded_url = rawurlencode($url);

// Consumer which is generated on wordpress
$consumer_key    = '';
$consumer_secret = '';

// Values which you get by earlier script
$oauth_token    = '';
$oauth_secret   = '';
$oauth_verifier = '';

// Request Body
$params = [
    'oauth_nonce'               => uniqid(),
    'oauth_signature_method'    => 'HMAC-SHA1',
    'oauth_timestamp'           => time(),
    'oauth_consumer_key'        => $consumer_key,
    'oauth_token'               => $oauth_token,
    'oauth_verifier'            => $oauth_verifier,
    'q'                         => '/oauth1/access',
];

// Order by key
ksort($params);

// Convert parameters from array to string
$queries = [];
foreach( $params as $key => $value ) {
    $queries[] = "$key=" . rawurlencode($value);
}
$str_params = rawurlencode(implode('&', $queries));

// Concat
$raw_signature = "{$method}&{$encoded_url}&{$str_params}";

// Make secret key
$secret_key = "{$consumer_secret}&{$oauth_secret}";

// Make signature
$signature = rawurlencode(base64_encode(hash_hmac('sha1', $raw_signature, $secret_key, true)));

// Add signature to request body
$params['oauth_signature'] = $signature;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

$output = curl_exec($ch);
var_dump($output);
