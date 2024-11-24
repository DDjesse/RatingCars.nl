<?php
session_start(); // Zorg ervoor dat de sessie is gestart

// Controleer of de gebruiker is ingelogd en een admin is
if (!isset($_SESSION['gebruikersnaam']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Als de gebruiker niet ingelogd is of geen admin is, stuur naar loginpagina
    exit;
}

require_once '../includes/db.php';

// Variabelen voor foutmeldingen en succes
$succes = '';
$fout = '';

// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titel = $_POST['titel'];
    $afbeelding = $_FILES['afbeelding'];

    // Valideer of de titel en afbeelding zijn ingevuld
    if (empty($titel) || empty($afbeelding['name'])) {
        $fout = 'Titel en afbeelding zijn verplicht!';
    } else {
        // Verplaats de afbeelding naar de juiste map
        $doelmap = '../images/';
        $bestandsnaam = basename($afbeelding['name']);
        $doelpunt = $doelmap . $bestandsnaam;

        // Controleer of het bestand een geldig afbeeldingsformaat is
        $imageFileType = strtolower(pathinfo($doelpunt, PATHINFO_EXTENSION));
        $validTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $validTypes)) {
            $fout = 'Alleen JPG, JPEG, PNG en GIF bestanden zijn toegestaan.';
        } elseif (move_uploaded_file($afbeelding['tmp_name'], $doelpunt)) {
            // Voeg de auto toe aan de database
            $query = $pdo->prepare("INSERT INTO posts (titel, afbeelding) VALUES (?, ?)");
            if ($query->execute([$titel, 'images/' . $bestandsnaam])) {
                $succes = 'Auto is succesvol toegevoegd!';
            } else {
                $fout = 'Er is een fout opgetreden bij het toevoegen van de auto.';
            }
        } else {
            $fout = 'Er is een probleem met het uploaden van de afbeelding.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paneel - Auto Toevoegen</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>Ratingcars.nl - Paneel</h1>
        </div>
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="Paneel.php">Paneel</a>
            <a href="logout.php">Uitloggen</a>
        </nav>
    </header>

    <main>
        <section class="paneel">
            <h2>Auto Toevoegen</h2>

            <!-- Succes of foutmelding weergeven -->
            <?php if ($succes): ?>
                <p style="color: green;"><?= $succes; ?></p>
            <?php endif; ?>
            <?php if ($fout): ?>
                <p style="color: red;"><?= $fout; ?></p>
            <?php endif; ?>

            <!-- Formulier voor het toevoegen van een auto -->
            <form action="paneel.php" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="titel">Titel van de auto:</label>
                    <input type="text" name="titel" id="titel" required>
                </div>

                <div>
                    <label for="afbeelding">Afbeelding van de auto:</label>
                    <input type="file" name="afbeelding" id="afbeelding" accept="image/*" required>
                </div>

                <button type="submit">Toevoegen</button>
            </form>
        </section>
    </main>
</body>
</html>
