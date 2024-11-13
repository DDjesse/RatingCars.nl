<?php
// Include de databaseverbinding
include '../includes/db.php';

// Controleer of het formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verkrijg de gegevens uit het formulier
    $naam = $_POST['naam'];
    $beschrijving = $_POST['beschrijving'];

    // Verwerk de afbeelding
    if (isset($_FILES['afbeelding']) && $_FILES['afbeelding']['error'] == 0) {
        $afbeelding_tmp_naam = $_FILES['afbeelding']['tmp_name'];
        $afbeelding_bestandstype = $_FILES['afbeelding']['type'];
        
        // Lees de afbeelding in als binaire data
        $afbeelding_data = file_get_contents($afbeelding_tmp_naam);
        
        // Voeg de auto toe aan de database met de afbeelding als BLOB
        $sql = "INSERT INTO autos (naam, beschrijving, afbeelding, likes, dislikes)
                VALUES ('$naam', '$beschrijving', ?, 0, 0)";
        
        // Voorbereiden van de statement om de binaire data veilig in te voegen
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $afbeelding_data); // 's' is voor string (binair is in feite een string)
        
        if ($stmt->execute()) {
            echo "Nieuwe auto succesvol toegevoegd!";
        } else {
            echo "Fout bij toevoegen van auto: " . $conn->error;
        }
    } else {
        echo "Geen afbeelding geüpload of een fout opgetreden.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Toevoegen</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Koppeling naar de externe CSS -->
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="logo.png" alt="Logo" class="logo-img">
        </div>
        <div class="nav-links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="paneel.php" class="nav-link">Paneel</a>
        </div>
    </nav>

    <h1>Voeg een nieuwe auto toe</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="naam">Auto Naam:</label>
        <input type="text" id="naam" name="naam" required><br>

        <label for="beschrijving">Beschrijving:</label>
        <textarea id="beschrijving" name="beschrijving" required></textarea><br>

        <label for="afbeelding">Afbeelding (bestand uploaden):</label>
        <input type="file" id="afbeelding" name="afbeelding" required><br>

        <button type="submit">Voeg Auto Toe</button>
    </form>
</body>
</html>
