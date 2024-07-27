<?php
include 'RSA.php';

$rsa = new RSA();
$publicKey = $rsa->getPublicKey();
$privateKey = $rsa->getPrivateKey();

file_put_contents('public_key.pem', json_encode($publicKey));
file_put_contents('private_key.pem', json_encode($privateKey));

echo "Keys generated and saved.";
?>
