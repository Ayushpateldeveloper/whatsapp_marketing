<!-- <?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'database.php';

/** Configuration */
define('WHAPI_BASE', 'https://gate.whapi.cloud/messages');
define('AUTH_TOKEN', 'ui9dhAL3NdNUVhigto5myAleEnkv6QNa');

/** Function to determine the correct endpoint based on media type */
if (isset($_POST['mediaUrl']) && isset($_POST['contacts']) && isset($_POST['message'])) {
    $mediaUrl = $_POST['mediaUrl'];
    $contacts = $_POST['contacts'];
    $message = $_POST['message'];





function getWhapiEndpoint($mediaUrl)
{
    // Default to text message if no media
    if (empty($mediaUrl)) {
        return WHAPI_BASE . '/text';
    }

    // Check file extension
    $extension = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));

    // Image formats
    if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
        return WHAPI_BASE . '/image';
    }

    // Video formats
    if (in_array($extension, ['mp4', 'mov', '3gp'])) {
        return WHAPI_BASE . '/video';
    }

    // Audio formats
    if (in_array($extension, ['mp3', 'ogg', 'wav'])) {
        return WHAPI_BASE . '/audio';
    }

    // Document formats
    if (in_array($extension, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'])) {
        return WHAPI_BASE . '/document';
    }

    // GIF format
    if ($extension === 'gif') {
        return WHAPI_BASE . '/gif';
    }

    // Sticker format (assuming webp or specific sticker format)
    if ($extension === 'webp' && strpos($mediaUrl, 'sticker') !== false) {
        return WHAPI_BASE . '/sticker';
    }

    // Default to image if type cannot be determined
    return WHAPI_BASE . '/image';
}

/** Dynamic Input */
$phoneNumber = '917990116127';
$textMessage = 'Hello! This is a demo text message accompanying the media.';
// $mediaUrl = 'https://movies.alyanka.com/hollywood/demo.mp4';
// $mediaUrl = 'https://media1.tenor.com/m/-qa2Zg3s2GQAAAAC/houston-texans-go-texans.gif';
// $mediaUrl = 'https://codeskulptor-demos.commondatastorage.googleapis.com/GalaxyInvaders/theme_01.mp3';
$mediaUrl = 'https://web.stanford.edu/class/cs142/lectures/CSS.pdf';

try {
    // Get the correct endpoint based on media type
    $endpoint = getWhapiEndpoint($mediaUrl);

    // Combine text message and media
    $payload = [
        'caption' => $textMessage,
        'to' => $phoneNumber,
        'media' => $mediaUrl
    ];

    // Send the request
    $response = sendMediaMessage($endpoint, $payload, AUTH_TOKEN);

    if (!$response['success']) {
        throw new Exception('Failed to send media message. ' . $response['message']);
    }

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Media message sent successfully!',
        'response' => $response
    ]);
} catch (Exception $e) {
    // Failure response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Function to send media message
 */
function sendMediaMessage($url, $data, $authToken)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'accept: application/json',
            'authorization: Bearer ' . $authToken,
            'content-type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        return [
            'success' => false,
            'message' => 'cURL Error #: ' . $err
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


    echo json_encode([
        'success' => true,
        'message' => 'Campaign sent successfully',
        'data' => [
            'mediaUrl' => $mediaUrl,
            'contacts' => $contacts,
            'message' => $message,
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Required parameters missing']);
    exit;
}
}

}
?> -->
