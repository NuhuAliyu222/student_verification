<?php
$to = "nuhualiyu222@gmail.com";
$subject = "Test Email";
$message = "This is a test email from PHP.";
$headers = "From: test@localhost.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Email failed to send.";
    
    // Check PHP configuration
    echo "<br><br>PHP Configuration:<br>";
    echo "SMTP: " . ini_get('SMTP') . "<br>";
    echo "smtp_port: " . ini_get('smtp_port') . "<br>";
    echo "sendmail_from: " . ini_get('sendmail_from') . "<br>";
}
?>