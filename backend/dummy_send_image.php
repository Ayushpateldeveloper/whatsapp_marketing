<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

// API credentials
$apiUrl = 'https://gate.whapi.cloud/messages/image';
$authToken = 'ui9dhAL3NdNUVhigto5myAleEnkv6QNa';

try {
	// Set data
	$phoneNumber = '917990116127';
	$imagePath = 'C:\xampp\htdocs\whatsapp_management\uploads\images\demo.jpg';

	// Check if file exists
	if (!file_exists($imagePath)) {
		throw new Exception("Image file not found at specified path");
	}

	// Convert image to base64
	$imageData = file_get_contents($imagePath);
	$base64Image = base64_encode($imageData);

	// Prepare the payload for image message
	$data = [
		'to' => $phoneNumber,
		'media' => 'data:image/jpeg;base64,' . $base64Image,
		'caption' => 'Image Caption'  // Optional caption for the image
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
			'message' => "Image message sent successfully",
			'response' => json_decode($apiResponse, true)
		]);
	} else {
		throw new Exception("Failed to send image message. HTTP Code: $httpCode, Response: $apiResponse");
	}

} catch (Exception $e) {
	echo json_encode([
		'success' => false,
		'message' => $e->getMessage()
	]);
}
?>