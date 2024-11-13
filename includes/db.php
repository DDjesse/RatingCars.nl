<?php
// Verbind met de database
$servername = "localhost";
$username = "root";  // Gebruik je eigen database gebruikersnaam
$password = "";      // Gebruik je eigen wachtwoord
$dbname = "cars_db"; // De naam van je database

// Maak de verbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer of de verbinding gelukt is
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
?>
