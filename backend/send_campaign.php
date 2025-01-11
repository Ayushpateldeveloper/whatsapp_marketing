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
