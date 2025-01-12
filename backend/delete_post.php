<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once 'database.php';

try {
	if (!isset($_POST['id'])) {
		throw new Exception('Post ID is required');
	}

	$postId = (int)$_POST['id'];

	// Get post data to delete associated media file
	$stmt = $conn->prepare("SELECT media_url FROM posts WHERE id = ?");
	$stmt->bind_param("i", $postId);
	$stmt->execute();
	$result = $stmt->get_result();
	$post = $result->fetch_assoc();

	if (!$post) {
		throw new Exception('Post not found');
	}

	// Delete associated media file if exists
	if ($post['media_url']) {
		$filePath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/whatsapp_management/uploads/', '../uploads/', $post['media_url']);
		if (file_exists($filePath)) {
			unlink($filePath);
		}
	}

	// Delete from database
	$stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
	if (!$stmt) {
		throw new Exception('Database error: ' . $conn->error);
	}

	$stmt->bind_param("i", $postId);
	if (!$stmt->execute()) {
		throw new Exception('Failed to delete post');
	}

	echo json_encode([
		'success' => true,
		'message' => 'Post deleted successfully'
	]);

} catch (Exception $e) {
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage()
	]);
}
?>