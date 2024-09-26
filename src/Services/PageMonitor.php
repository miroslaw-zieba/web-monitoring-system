<?php

namespace Services;

// PageMonitor class responsible for monitoring website pages and logging results
class PageMonitor
{
    private Database $db; // Database connection instance
    private EmailNotifier $notifier; // Email notification instance

    // Constructor method initializes the database and email notifier dependencies
    public function __construct(Database $db, EmailNotifier $notifier)
    {
        $this->db = $db; // Assign the provided database connection
        $this->notifier = $notifier; // Assign the provided email notifier instance
    }

    // Main method to monitor all pages retrieved from the database
    public function monitor(): void
    {
        $pages = $this->db->getPages(); // Get the list of pages to be monitored from the database
        foreach ($pages as $page) {
            $this->monitorPage($page); // Monitor each page individually
        }
    }

    // Private method to monitor a specific page
    private function monitorPage(array $page): void
    {
        $start_time = microtime(true); // Record the start time to measure load time

        // Initialize a cURL session for fetching the page content
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $page['url']); // Set the URL to be monitored
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the content as a string
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow any redirects
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set the maximum execution time to 30 seconds
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Set the connection timeout to 10 seconds
        $response = curl_exec($ch); // Execute the cURL request and get the page content
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP response code
        $load_time = microtime(true) - $start_time; // Calculate the page load time
        curl_close($ch); // Close the cURL session

        $error_message = ''; // Initialize error message
        $html_length = $response !== false ? strlen($response) : 0; // Calculate the length of the response HTML, or 0 if there's no response
        $text_found = null; // Initialize variable to check for optional text
        $admin_notified = 0; // Flag to track whether the admin has been notified

        // Check if the response failed or the HTTP status code is not 200 (OK)
        if ($response === false || $http_code !== 200) {
            // Set the appropriate error message
            $error_message = $response === false ? 'No response from the server' : "HTTP response code: $http_code";
            // Send an error notification email to the administrator
            $this->notifier->sendErrorEmail($page['url'], $error_message);
            $admin_notified = 1; // Set the flag that the admin has been notified
        } 
        // Check if there's optional text to look for in the response
        elseif ($page['optional_text']) {
            // Check if the optional text exists in the response
            $text_found = strpos($response, $page['optional_text']) !== false;
            // If the text is not found, send an error notification
            if (!$text_found) {
                $error_message = "Optional text '{$page['optional_text']}' not found on the page.";
                $this->notifier->sendErrorEmail($page['url'], $error_message); // Notify the admin
                $admin_notified = 1; // Set the flag that the admin has been notified
            }
        }

        // Log the monitoring result into the database
        $this->db->insertMonitoringResult([
            ':page_id' => $page['id'], // Page ID from the database
            ':response_code' => $http_code, // HTTP response code
            ':load_time' => $load_time, // Page load time
            ':html_length' => $html_length, // Length of the HTML content
            ':text_found' => $text_found, // Whether the optional text was found or not
            ':total_time' => microtime(true) - $start_time, // Total execution time
            ':error_message' => $error_message, // Any error message encountered
            ':admin_notified' => $admin_notified // Whether the admin was notified
        ]);
    }
}
