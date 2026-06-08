<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize inputs
    $fname   = htmlspecialchars(trim($_POST["fname"] ?? ""));
    $lname   = htmlspecialchars(trim($_POST["lname"] ?? ""));
    $email   = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars(trim($_POST["phone"] ?? ""));
    $message = htmlspecialchars(trim($_POST["message"] ?? ""));

    // Basic validation
    if (empty($fname) || empty($lname) || empty($email) || empty($phone)) {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email address."]);
        exit;
    }

    // Email settings
    $to      = "info@nathviasolutions.com"; // <-- recipient
    $subject = "New Contact Form Message from $fname $lname";
    $body    = "You have received a new message:\n\n"
             . "Name:    $fname $lname\n"
             . "Email:   $email\n"
             . "Phone:   $phone\n\n"
             . "Message:\n$message";

    $headers = "From: $email\r\n"
             . "Reply-To: $email\r\n"
             . "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success", "message" => "Your message was sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
    }

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
}
?>