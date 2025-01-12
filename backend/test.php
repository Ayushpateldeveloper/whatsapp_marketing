<?php
// Include database configuration and other necessary files
require_once 'database.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the submitted contacts, message, and media URL
    $contacts = isset($_POST['contacts']) ? $_POST['contacts'] : [];
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $mediaUrl = isset($_POST['mediaUrl']) ? $_POST['mediaUrl'] : '';

    // Display the data (contact numbers, message, and media URL)
    echo "<h2>Campaign Data</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($message) . "</p>";
    echo "<p><strong>Media URL:</strong> " . htmlspecialchars($mediaUrl) . "</p>";
    echo "<p><strong>SELECted Contacts:</strong> " . print_r($contacts). "</p>";


    if (!empty($contacts)) {
        echo "<h3>Selected Contacts:</h3>";
        echo "<ul>";
        foreach ($contacts as $contactId) {
            // Fetch contact details from the database using the contact ID
            $stmt = $conn->prepare("SELECT name, phone_number FROM contacts WHERE id = ?");
            $stmt->bind_param('i', $contactId);
            $stmt->execute();
            $result = $stmt->get_result();
            $contact = $result->fetch_assoc();

            if ($contact) {
                echo "<li><strong>Name:</strong> " . htmlspecialchars($contact['name']) . " | <strong>Phone Number:</strong> " . htmlspecialchars($contact['phone_number']) . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>No contacts selected.</p>";
    }
} else {
    echo "<p>No data submitted.</p>";
}
?>
