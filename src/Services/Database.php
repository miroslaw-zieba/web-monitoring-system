<?php

namespace Services;

use PDO; // Import the PDO class for database connection
use PDOException; // Import the PDOException class for handling connection errors

// Database class responsible for managing database connections and queries
class Database
{
    private PDO $pdo; // PDO instance for interacting with the database

    // Constructor method to initialize the database connection
    public function __construct(array $config)
    {
        try {
            // Data Source Name (DSN) for MySQL connection with UTF-8 charset
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8";
            // Create a new PDO instance with the provided configuration (host, dbname, user, pass)
            $this->pdo = new PDO($dsn, $config['user'], $config['pass']);
            // Set the PDO error mode to throw exceptions for better error handling
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // If the connection fails, output the error message and stop execution
            die("Database connection error: " . $e->getMessage());
        }
    }

    // Method to retrieve all pages that need to be monitored from the 'pages' table
    public function getPages(): array
    {
        // Prepare a SQL query to fetch 'id', 'url', and 'optional_text' fields from the 'pages' table
        $stmt = $this->pdo->prepare("SELECT id, url, optional_text FROM pages");
        // Execute the query
        $stmt->execute();
        // Fetch and return the results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to retrieve all admin email addresses from the 'admins' table
    public function getAdmins(): array
    {
        // Prepare a SQL query to fetch the 'email' field from the 'admins' table
        $stmt = $this->pdo->prepare("SELECT email FROM admins");
        // Execute the query
        $stmt->execute();
        // Fetch and return the email addresses as a simple array (1-dimensional)
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Method to insert monitoring results into the 'page_monitoring' table
    public function insertMonitoringResult(array $data): void
    {
        // SQL query to insert monitoring data into the 'page_monitoring' table with placeholders for dynamic data
        $sql = "INSERT INTO page_monitoring (page_id, response_code, load_time, html_length, text_found, total_time, checked_at, error_message, admin_notified)
                VALUES (:page_id, :response_code, :load_time, :html_length, :text_found, :total_time, NOW(), :error_message, :admin_notified)";
        // Prepare the SQL statement
        $stmt = $this->pdo->prepare($sql);
        // Execute the prepared statement with the provided data array
        $stmt->execute($data);
    }
}
