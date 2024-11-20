<?php
// db.php inladen voor databaseverbinding
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];
    $email = $_POST['email'];

    // Controleer of gebruikersnaam al bestaat
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE gebruikersnaam = :gebruikersnaam');
    $stmt->execute(['gebruikersnaam' => $gebruikersnaam]);
    if ($stmt->fetch()) {
        $foutmelding = 'Deze gebruikersnaam is al in gebruik';
    } else {
        // Wachtwoord hashen
        $wachtwoord_gehasht = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // Voeg de gebruiker toe aan de database
        $stmt = $pdo->prepare('INSERT INTO accounts (gebruikersnaam, wachtwoord, email) VALUES (:gebruikersnaam, :wachtwoord, :email)');
        $stmt->execute([
            'gebruikersnaam' => $gebruikersnaam,
            'wachtwoord' => $wachtwoord_gehasht,
            'email' => $email
        ]);

        // Redirect naar de loginpagina
        header('Location: login.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreer</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <div class="paneel">
            <h2>Registreren</h2>
            <?php if (isset($foutmelding)): ?>
                <p style="color: red;"><?php echo $foutmelding; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="gebruikersnaam">Gebruikersnaam</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required>

                <button type="submit">Registreren</button>
            </form>
            <p>Heb je al een account? <a href="login.php">Inloggen hier</a></p>
        </div>
    </main>
</body>
</html>
