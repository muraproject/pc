<?php
header('Content-Type: application/json');
echo file_get_contents('public_key.pem');
?>
