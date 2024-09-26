<?php

namespace Services;

// EmailNotifier class responsible for sending error notifications via email
class EmailNotifier
{
    private array $admins; // Array storing the admin email addresses
    private string $fromEmail; // The email address from which the alerts are sent

    // Constructor method to initialize the email notifier with admin emails and sender's email
    public function __construct(array $admins, string $fromEmail)
    {
        $this->admins = $admins; // Set the list of admin emails
        $this->fromEmail = $fromEmail; // Set the "from" email address for notifications
    }

    // Method to send an error notification email to all admins
    public function sendErrorEmail(string $pageUrl, string $errorMessage): void
    {
        // Email subject containing the page URL
        $subject = "ALERT: Issue with website availability: $pageUrl";

        // The message body of the email containing the error details
        $message = "A problem occurred with the monitored website: $pageUrl\n";
        $message .= "Details: $errorMessage\n"; // Append the specific error message
        $message .= "Time: " . date('Y-m-d H:i:s') . "\n\n"; // Append the current timestamp

        // Email headers, including the sender email and content type
        $headers = "From: {$this->fromEmail}\r\n"; // Set the "From" email header
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Specify plain text format with UTF-8 encoding

        // Loop through the list of admin emails and send the email to each one
        foreach ($this->admins as $email) {
            mail($email, $subject, $message, $headers); // Send the email to each admin
        }
    }
}
