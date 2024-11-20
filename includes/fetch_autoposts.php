<?php
require_once '../includes/db.php'; // Inclusie van je bestaande databaseverbinding

// Query om de posts op te halen, gesorteerd op datum_gemaakt van nieuw naar oud
$query = $pdo->query("SELECT * FROM posts ORDER BY datum_gemaakt DESC");

// Resultaten ophalen
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>
