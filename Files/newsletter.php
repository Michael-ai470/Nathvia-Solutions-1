<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_var(trim($_POST["mail"] ?? ""), FILTER_SANITIZE_EMAIL);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Please enter a valid email address."]);
        exit;
    }

    $to      = "info@nathviasolutions.com";
    $subject = "New Newsletter Subscription";
    $body    = "A new user has subscribed to the newsletter:\n\nEmail: $email";
    $headers = "From: $email\r\n"
             . "Reply-To: $email\r\n"
             . "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(["status" => "success", "message" => "You're subscribed! Thank you."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again."]);
    }

} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
}
?>