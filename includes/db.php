<?php
// Database-instellingen
$host = 'localhost'; // Of je eigen database host
$dbname = 'Autoposts'; // Jouw database naam
$username = 'root'; // Database gebruikersnaam
$password = ''; // Database wachtwoord

// Verbinding maken met de database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Foutmeldingen inschakelen
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>
