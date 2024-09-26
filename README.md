
# **Web Monitoring System by Mirosław Zięba**  

![GitHub license](https://img.shields.io/github/license/miroslaw-zieba/web-monitoring-system)  
![GitHub stars](https://img.shields.io/github/stars/miroslaw-zieba/web-monitoring-system)  

**Web Monitoring System** is a PHP-based solution designed to monitor website uptime, performance, and optional content validation. It logs important metrics such as response codes, load times, and error occurrences, and sends email alerts to administrators when issues are detected. The project is built with object-oriented principles to ensure scalability and maintainability, making it an ideal solution for monitoring multiple websites efficiently.  

## **Features:**  
- Monitors website uptime and response times.  
- Detects content changes or missing elements on monitored pages.  
- Logs detailed information about website performance and availability.  
- Sends email alerts to administrators in case of website failure or slow performance.  
- Flexible database structure with website categories for easy management.  
- Uses configurable timeout settings to ensure responsiveness.  

## **Installation:**  

1. Clone the repository:  
   ```bash  
   git clone https://github.com/miroslaw-zieba/web-monitoring-system.git  
   cd web-monitoring-system  
   ```  

2. Install dependencies using Composer:  
   ```bash  
   composer install  
   ```  

3. Set up your database by running the provided SQL script in `database.sql`.  

4. Configure your environment by editing the `Config/Config.php` file with your database and email settings.  

5. Set up a cron job to run the monitoring script periodically:  
   ```bash  
   */10 * * * * /usr/bin/php /path/to/web-monitoring-system/src/monitor.php  
   ```  

## **Database Setup:**  

Log in to your MySQL/MariaDB server and create the necessary database:  

```sql  
CREATE DATABASE monitoring;  
```  

Then, run the following SQL script to create the required tables:  

```sql  
CREATE TABLE categories (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    name VARCHAR(100) NOT NULL  
);  

CREATE TABLE websites (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    url VARCHAR(255) NOT NULL,  
    category_id INT,  
    FOREIGN KEY (category_id) REFERENCES categories(id)  
);  

CREATE TABLE page_monitoring (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    page_id INT,  
    response_code INT,  
    load_time FLOAT,  
    html_length INT,  
    text_found BOOLEAN,  
    total_time FLOAT,  
    checked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    error_message VARCHAR(255),  
    admin_notified BOOLEAN DEFAULT 0,  
    last_email_sent TIMESTAMP NULL,  
    response_time_threshold FLOAT DEFAULT NULL,  
    FOREIGN KEY (page_id) REFERENCES websites(id)  
);  

CREATE TABLE admins (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    name VARCHAR(100),  
    email VARCHAR(255)  
);  
```  

## **Usage:**  
- After the installation, the system will monitor the configured websites and log performance metrics.  
- The system sends email alerts when issues such as downtime, errors, or performance degradation are detected.  

## **License:**  
This project is licensed under the MIT License. See the LICENSE file for details.  
