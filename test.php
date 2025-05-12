<?php
require_once 'app/core/Config.php';
require_once 'app/core/Encryption.php';

// Define the $config array exactly as your app expects
global $config;
$config = [
    'encryption' => [
        'encryption_key' => '3fT!9@eL#4vR%8c2*7m%Qa0nZ1p',
        'hmac_salt' => 'b7E3d8r5*Lm!9v@4Yq6&Mp2zZx1o',
        'hash_key' => 'R8nP6qW!7g#Xz3Ml'
    ]
];

// Now run the encryption
$id = "123";

$encrypted = Encryption::encrypt($id);
echo "Encrypted: " . $encrypted . "<br>";

$decrypted = Encryption::decrypt($encrypted);
echo "Decrypted: " . $decrypted . "<br>";
