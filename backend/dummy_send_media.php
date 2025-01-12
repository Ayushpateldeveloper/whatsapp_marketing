<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

/**
 * Configuration
 */
define('WHAPI_BASE', 'https://gate.whapi.cloud/messages');
define('AUTH_TOKEN', 'NXj2WNAtjJRaMICuVXC35SZxCaFXk6Y9');

/**
 * Function to determine the correct endpoint based on media type
 */
function getWhapiEndpoint($mediaUrl) {
    if (empty($mediaUrl)) {
        return WHAPI_BASE . '/text';
    }

    $extension = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));

    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
        return WHAPI_BASE . '/image';
    }

    if (in_array($extension, ['mp4', 'mov', '3gp'])) {
        return WHAPI_BASE . '/video';
    }

    if (in_array($extension, ['mp3', 'ogg', 'wav'])) {
        return WHAPI_BASE . '/audio';
    }

    if (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
        return WHAPI_BASE . '/document';
    }

    if ($extension === 'gif') {
        return WHAPI_BASE . '/gif';
    }

    if ($extension === 'webp' && strpos($mediaUrl, 'sticker') !== false) {
        return WHAPI_BASE . '/sticker';
    }

    return WHAPI_BASE . '/image';
}

/**
 * Function to send media message
 */
function sendMediaMessage($url, $data, $authToken) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
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
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return [
            'success' => false,
            'message' => "cURL Error #: " . $err
        ];
    }

    if ($httpCode === 200) {
        return [
            'success' => true,
            'response' => json_decode($response, true)
        ];
    } else {
        return [
            'success' => false,
            'message' => $response
        ];
    }
}

/**
 * Dynamic Input
 */
$phoneNumbers = ['917990116127', '919664876345']; // Add multiple phone numbers here
$textMessage = 'Hello! This is a demo text message accompanying the media.';
$mediaUrl = 'https://web.stanford.edu/class/cs142/lectures/CSS.pdf';

$results = [];

try {
    $endpoint = getWhapiEndpoint($mediaUrl);

    foreach ($phoneNumbers as $phoneNumber) {
        $payload = [
            'caption' => $textMessage,
            'to' => $phoneNumber,
            'media' => $mediaUrl
        ];

        $response = sendMediaMessage($endpoint, $payload, AUTH_TOKEN);

        $results[] = [
            'phone' => $phoneNumber,
            'success' => $response['success'],
            'message' => $response['success'] ? 'Media message sent successfully!' : $response['message']
        ];
    }

    echo json_encode([
        'success' => true,
        'results' => $results
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}