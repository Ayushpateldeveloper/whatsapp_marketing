<<<<<<< HEAD
<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'database.php';

// API credentials
$apiUrl = 'https://gate.whapi.cloud/messages/send';
$authToken = 'ui9dhAL3NdNUVhigto5myAleEnkv6QNa';

try {
	if (!isset($_POST['contacts']) || !isset($_POST['message'])) {
		throw new Exception('Missing required parameters');
	}

	$contacts = json_decode($_POST['contacts'], true);
	if (!$contacts) {
		throw new Exception('Invalid contacts data');
	}

	$messageText = trim($_POST['message']);
	if (empty($messageText)) {
		throw new Exception('Message cannot be empty');
	}

	// Handle file upload if present
	$mediaUrl = null;
	if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
		$uploadDir = '../uploads/';
		if (!file_exists($uploadDir)) {
			if (!mkdir($uploadDir, 0777, true)) {
				throw new Exception('Failed to create upload directory');
			}
		}
		
		$fileName = uniqid() . '_' . basename($_FILES['file']['name']);
		$uploadFile = $uploadDir . $fileName;
		
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
			throw new Exception('Failed to upload file');
		}
		$mediaUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/whatsapp_management/uploads/' . $fileName;
	}

	// Get contact phone numbers from database
	$contactIds = implode(',', array_map('intval', $contacts));
	$stmt = $conn->prepare("SELECT phone_number FROM contacts WHERE id IN ($contactIds)");
	if (!$stmt) {
		throw new Exception('Database error: ' . $conn->error);
	}
	
	$stmt->execute();
	$result = $stmt->get_result();

	$successCount = 0;
	$failCount = 0;
	$errors = [];

	while ($contact = $result->fetch_assoc()) {
		// Generate a unique message ID
		$messageId = 'MSG_' . uniqid() . '_' . time();
		
		// Prepare the payload according to API requirements
		$data = [
			'to' => $contact['phone_number'],
			'type' => 'text',
			'text' => [
				'body' => $messageText
			],
			'message_id' => $messageId
		];

		// Add media if present
		if ($mediaUrl) {
			$data['type'] = 'image';
			$data['image'] = [
				'url' => $mediaUrl
			];
		}
		
		$ch = curl_init($apiUrl);
		curl_setopt_array($ch, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				'Authorization: Bearer ' . $authToken,
				'Content-Type: application/json'
			],
			CURLOPT_POSTFIELDS => json_encode($data)
		]);
		
		$apiResponse = curl_exec($ch);
		
		if (curl_errno($ch)) {
			$errors[] = curl_error($ch);
			$failCount++;
		} else {
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($httpCode === 200) {
				$successCount++;
			} else {
				$failCount++;
				$errors[] = "HTTP Code: $httpCode, Response: $apiResponse";
			}
		}
		
		curl_close($ch);
	}

	echo json_encode([
		'success' => $successCount > 0,
		'message' => "Successfully sent to $successCount contacts." . 
					($failCount > 0 ? " Failed for $failCount contacts." : ""),
		'errors' => $errors
	]);

} catch (Exception $e) {
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage(),
		'errors' => [$e->getMessage()]
	]);
}
?>
=======
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
>>>>>>> 228c558 (updated)
