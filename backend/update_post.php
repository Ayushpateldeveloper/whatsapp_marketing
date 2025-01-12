<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'database.php';

try {
	if (!isset($_POST['postId']) || !isset($_POST['message'])) {
		throw new Exception('Post ID and message are required');
	}

	$postId = (int)$_POST['postId'];
	$messageText = trim($_POST['message']);
	
	if (empty($messageText)) {
		throw new Exception('Message cannot be empty');
	}

	// Get existing post data
	$stmt = $conn->prepare("SELECT media_url, media_type FROM posts WHERE id = ?");
	$stmt->bind_param("i", $postId);
	$stmt->execute();
	$result = $stmt->get_result();
	$existingPost = $result->fetch_assoc();

	if (!$existingPost) {
		throw new Exception('Post not found');
	}

	$mediaUrl = $existingPost['media_url'];
	$mediaType = $existingPost['media_type'];

	// Handle new media file upload if provided
	if (isset($_FILES['mediaFile']) && $_FILES['mediaFile']['error'] === UPLOAD_ERR_OK) {
		// Delete old media file if exists
		if ($existingPost['media_url']) {
			$oldFilePath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/whatsapp_management/uploads/', '../uploads/', $existingPost['media_url']);
			if (file_exists($oldFilePath)) {
				unlink($oldFilePath);
			}
		}

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

	// Update database
	$stmt = $conn->prepare("UPDATE posts SET message = ?, media_url = ?, media_type = ? WHERE id = ?");
	if (!$stmt) {
		throw new Exception('Database error: ' . $conn->error);
	}

	$stmt->bind_param("sssi", $messageText, $mediaUrl, $mediaType, $postId);
	if (!$stmt->execute()) {
		throw new Exception('Failed to update post');
	}

	echo json_encode([
		'success' => true,
		'message' => 'Post updated successfully',
		'data' => [
			'id' => $postId,
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