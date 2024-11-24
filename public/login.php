<?php
// db.php inladen voor databaseverbinding
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // Zoek de gebruiker in de database
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE gebruikersnaam = :gebruikersnaam');
    $stmt->execute(['gebruikersnaam' => $gebruikersnaam]);
    $account = $stmt->fetch();

    // Controleer of het wachtwoord klopt en of de gebruiker een admin is
    if ($account && password_verify($wachtwoord, $account['wachtwoord'])) {
        session_start();
        $_SESSION['id'] = $account['id'];  // Sla de gebruiker ID op in de sessie
        $_SESSION['gebruikersnaam'] = $gebruikersnaam;
        $_SESSION['role'] = $account['role'];  // Sla de rol van de gebruiker op in de sessie

        // Als de gebruiker een admin is, stuur door naar de admin-pagina, anders naar de gewone index
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../public/paneel.php');  // Redirect naar de admin pagina
        } else {
            header('Location: ../public/index.php');  // Redirect naar de gewone homepage
        }
        exit;
    } else {
        $foutmelding = 'Ongeldige gebruikersnaam of wachtwoord';
    }
}
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <main>
        <div class="paneel">
            <h2>Inloggen</h2>
            <?php if (isset($foutmelding)): ?>
                <p style="color: red;"><?php echo $foutmelding; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="gebruikersnaam">Gebruikersnaam</label>
                <input type="text" id="gebruikersnaam" name="gebruikersnaam" required>

                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" id="wachtwoord" name="wachtwoord" required>

                <button type="submit">Inloggen</button>
            </form>
            <p>Heb je nog geen account? <a href="registreer.php">Registreer hier</a></p>
        </div>
    </main>
</body>
</html>
