<?php
include '../includes/db.php';  // Dit bestand zou de verbinding moeten initialiseren


// Like of dislike bijwerken
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['like']) || isset($_POST['dislike'])) {
        $auto_id = $_POST['auto_id'];
        if (isset($_POST['like'])) {
            $sql = "UPDATE autos SET likes = likes + 1 WHERE id = $auto_id";
        } elseif (isset($_POST['dislike'])) {
            $sql = "UPDATE autos SET dislikes = dislikes + 1 WHERE id = $auto_id";
        }

        if ($conn->query($sql) === TRUE) {
            echo "De actie is succesvol uitgevoerd!";
        } else {
            echo "Fout bij het bijwerken van de gegevens: " . $conn->error;
        }
    }
}

// Haal de auto's op uit de database
$sql = "SELECT * FROM autos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto's Overzicht</title>
    <link rel="stylesheet" href="../css/style.css">
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

    <main class="main-content">
        <h1>Auto Overzicht</h1>
        
        <table class="car-table">
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <div class="card">
                                <?php 
                                // Als de afbeelding een BLOB is in de database:
                                $afbeelding_data = $row['afbeelding'];
                                $afbeelding_base64 = base64_encode($afbeelding_data); // Convert BLOB naar base64

                                echo '<img src="data:image/jpeg;base64,' . $afbeelding_base64 . '" alt="Auto" class="card-img">';
                                ?>
                                <h2 class="card-title"><?php echo $row['naam']; ?></h2>
                                <p><?php echo $row['beschrijving']; ?></p>
                                <div class="card-actions">
                                    <form action="index.php" method="POST" style="display: inline;">
                                        <button type="submit" name="like" class="button like-btn">
                                            👍
                                        </button>
                                        <span><?php echo $row['likes']; ?></span>
                                        <input type="hidden" name="auto_id" value="<?php echo $row['id']; ?>">
                                    </form>
                                    <form action="index.php" method="POST" style="display: inline;">
                                        <button type="submit" name="dislike" class="button dislike-btn">
                                            👎
                                        </button>
                                        <span><?php echo $row['dislikes']; ?></span>
                                        <input type="hidden" name="auto_id" value="<?php echo $row['id']; ?>">
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

</body>
</html>

<?php
$conn->close();
?>
