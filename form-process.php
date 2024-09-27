<?php
if (isset($_POST['email'])) {

    function clean_string($string) {
        $bad = array("content-type", "bcc:", "to:", "cc:", "href");
        return str_replace($bad, "", $string);
    }

    function problem($error) {
        echo "Oh looks like there is some problem with your form data: <br><br>";
        echo $error . "<br><br>";
        echo "Please fix those to proceed.<br><br>";
        die();
    }

    // Validation expected data exists
    if (
        !isset($_POST['fullName']) ||
        !isset($_POST['email']) ||
        !isset($_POST['subject']) ||
        !isset($_POST['message'])
    ) {
        problem('Oh looks like there is some problem with your form data.');
    }

    $name = $_POST['fullName']; // required
    $email = $_POST['email']; // required
    $subject = $_POST['subject']; // required
    $message = $_POST['message']; // required

    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if (!preg_match($email_exp, $email)) {
        $error_message .= 'Email address does not seem valid.<br>';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if (!preg_match($string_exp, $name)) {
        $error_message .= 'Name does not seem valid.<br>';
    }

    if (strlen($message) < 2) {
        $error_message .= 'Message should not be less than 2 characters<br>';
    }

    if (strlen($error_message) > 0) {
        problem($error_message);
    }

    // Prepare the data to be saved
    $data_to_save = "Name: " . clean_string($name) . "\n";
    $data_to_save .= "Email: " . clean_string($email) . "\n";
    $data_to_save .= "Subject: " . clean_string($subject) . "\n";

    $data_to_save .= "Message: " . clean_string($message) . "\n";
    $data_to_save .= "------------------------\n";

    // Save the data to a file
    $file = 'submissions.txt'; // File to save the input
    file_put_contents($file, $data_to_save, FILE_APPEND | LOCK_EX);

    // Success message
    echo "Thanks for contacting us, your message has been saved.";
}
?>