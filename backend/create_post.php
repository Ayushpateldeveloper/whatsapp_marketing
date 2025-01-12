<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'database.php';

// API credentials
$authToken = 'ui9dhAL3NdNUVhigto5myAleEnkv6QNa';

try {
	if (!isset($_POST['message'])) {
		throw new Exception('Message is required');
	}

	$messageText = trim($_POST['message']);
	if (empty($messageText)) {
		throw new Exception('Message cannot be empty');
	}

	// Handle media file upload
	$mediaUrl = null;
	$mediaType = null;
	if (isset($_FILES['mediaFile']) && $_FILES['mediaFile']['error'] === UPLOAD_ERR_OK) {
		$uploadDir = '../uploads/';
		$fileType = strtolower(pathinfo($_FILES['mediaFile']['name'], PATHINFO_EXTENSION));
		
		// Determine media type
		if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
			$mediaType = 'image';
			$typeDir = $uploadDir . 'images/';
		} elseif (in_array($fileType, ['mp4', 'mov', 'avi'])) {
			$mediaType = 'video';
			$typeDir = $uploadDir . 'videos/';
		} elseif (in_array($fileType, ['mp3', 'wav'])) {
			$mediaType = 'audio';
			$typeDir = $uploadDir . 'audio/';
		} else {
			throw new Exception('Unsupported file type');
		}

		// Create directory if it doesn't exist
		if (!file_exists($typeDir)) {
			if (!mkdir($typeDir, 0777, true)) {
				throw new Exception('Failed to create upload directory');
			}
		}

		// Generate unique filename
		$fileName = uniqid() . '_' . basename($_FILES['mediaFile']['name']);
		$uploadFile = $typeDir . $fileName;

		// Upload file
		if (!move_uploaded_file($_FILES['mediaFile']['tmp_name'], $uploadFile)) {
			throw new Exception('Failed to upload file');
		}

		$mediaUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/whatsapp_management/uploads/' . $mediaType . 's/' . $fileName;
	}

	// Store in database (you might want to create a posts table)
	$stmt = $conn->prepare("INSERT INTO posts (message, media_url, media_type, created_at) VALUES (?, ?, ?, NOW())");
	if (!$stmt) {
		throw new Exception('Database error: ' . $conn->error);
	}

	$stmt->bind_param("sss", $messageText, $mediaUrl, $mediaType);
	if (!$stmt->execute()) {
		throw new Exception('Failed to save post');
	}

	echo json_encode([
		'success' => true,
		'message' => 'Post created successfully',
		'data' => [
			'message' => $messageText,
			'mediaUrl' => $mediaUrl,
			'mediaType' => $mediaType
		]
	]);

} catch (Exception $e) {
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage()
	]);
}
?>