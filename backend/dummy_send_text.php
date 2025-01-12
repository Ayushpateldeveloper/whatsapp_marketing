
<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

// API credentials
$apiUrl = 'https://gate.whapi.cloud/messages/text';
$authToken = 'ui9dhAL3NdNUVhigto5myAleEnkv6QNa';

try {
    // Set dummy data
    $phoneNumber = '917990116127';
    $messageText = 'hi';

    // Prepare the payload for text message
    $data = [
        'typing_time' => 0,
        'to' => $phoneNumber,
        'body' => $messageText
    ];

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer " . $authToken,
            "content-type: application/json"
        ]
    ]);
    
    $apiResponse = curl_exec($ch);
    
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        echo json_encode([
            'success' => true,
            'message' => "Message sent successfully",
            'response' => json_decode($apiResponse, true)
        ]);
    } else {
        throw new Exception("Failed to send message. HTTP Code: $httpCode, Response: $apiResponse");
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>