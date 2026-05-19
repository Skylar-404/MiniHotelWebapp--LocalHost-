🏨 Hotel Management System

Welcome to the Hotel Management System repository!
This project is built using PHP and MySQL, designed to manage hotel bookings, rooms, payments, customers, and maintenance records.
🚀 Getting Started
1. Clone the Repository

Use the following command in your terminal or command prompt:
bash

git clone https://github.com/your-username/your-repo-name.git

2. Create the Database

    Open your database management tool (e.g., phpMyAdmin).

    Import the schema from the provided file:

text

DBschema.txt

This will create all necessary tables for the system.
3. Configure Database Connection

Edit the file:
text

/layout/connection.php

Fill in your database connection details:
php

$port   = "3306";       // Your MySQL port
$user   = "root";       // Your MySQL username
$pw     = "password";   // Your MySQL password
$dbname = "hotel_db";   // Your database name

4. Run the Application

Once the database is set up and connection configured, launch the system by opening:
text

hotel.php

in your browser (e.g., http://localhost/hotel.php).
📂 Project Structure

    hotel.php → Main entry point

    layout/ → Contains connection and layout files

    bookinglist.php, addbooking.php → Booking management

    manageroom.php, editroom.php → Room management

    payment.php → Payment handling

    maintenance.php → Maintenance records

    cusdetail.php → Customer details

    loginpage.php → Admin login
