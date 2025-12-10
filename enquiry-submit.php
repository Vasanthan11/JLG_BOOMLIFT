<?php
// enquiry-submit.php

// Simple helper for sanitising
function clean_input($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect fields
    $fullName  = isset($_POST['fullName']) ? clean_input($_POST['fullName']) : '';
    $email     = isset($_POST['email']) ? clean_input($_POST['email']) : '';
    $phone     = isset($_POST['phone']) ? clean_input($_POST['phone']) : '';
    $location  = isset($_POST['location']) ? clean_input($_POST['location']) : '';
    $equipment = isset($_POST['equipment']) ? clean_input($_POST['equipment']) : '';
    $message   = isset($_POST['message']) ? clean_input($_POST['message']) : '';

    // Basic required fields check
    if ($fullName === '' || $phone === '') {
        // Missing required fields – you can redirect back or show a basic error
        // For now, just stop with a simple message
        echo "Please fill in the required fields (Full Name and Contact Number).";
        exit;
    }

    // Where to send the enquiry
    $to = "info@jigboomlift.in"; // change if you want another address

    $subject = "New JLG Boomlifts Enquiry from " . $fullName;

    // Build the email body
    $body  = "You have received a new enquiry from the website.\n\n";
    $body .= "Full Name: " . $fullName . "\n";
    $body .= "Email: " . ($email ?: "Not provided") . "\n";
    $body .= "Phone: " . $phone . "\n";
    $body .= "Site Location: " . ($location ?: "Not provided") . "\n";
    $body .= "Equipment Type: " . ($equipment ?: "Not specified") . "\n\n";
    $body .= "Project Details:\n" . ($message ?: "Not provided") . "\n";

    // Basic headers
    $headers   = "From: no-reply@jigboomlift.in\r\n";
    if (!empty($email)) {
        $headers .= "Reply-To: " . $email . "\r\n";
    }

    // Try sending the mail
    @mail($to, $subject, $body, $headers);

    // Redirect to thank you page
    header("Location: thankyou.html");
    exit;
} else {
    // If accessed directly (not via POST), redirect to main page or form
    header("Location: index.html");
    exit;
}
