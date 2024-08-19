<?php
//http://localhost/pc/telegram/send_telegram.php?message=kaika%20esmola

function sendTelegramMessage($message) {
    $botToken = "7116196273:AAHK7p3VgwWEGwkcgmWZGtp93SQgsKFJwSU";
    $chatID = "-1002147764120";
    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

    $postData = array(
        'chat_id' => $chatID,
        'text' => $message
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}

// Cek apakah ada parameter 'message' dalam GET request
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    
    // Untuk keamanan, bersihkan input
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    $result = sendTelegramMessage($message);
    
    $response = json_decode($result, true);
    
    if ($response && $response['ok']) {
        echo "succes";
    } else {
        echo "failed " . ($response['description'] ?? 'Unknown error');
    }
} else {
    echo "no parameter";
}

?>