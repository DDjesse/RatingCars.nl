<?php
session_start();
require_once '../includes/db.php';

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['id'])) {
    echo "Je moet ingelogd zijn om te stemmen.";
    exit;
}

// Verkrijg de lijst van posts
$query = $pdo->query("SELECT * FROM posts ORDER BY datum_gemaakt DESC");
$posts = $query->fetchAll(PDO::FETCH_ASSOC);

// Verkrijg de gebruiker ID uit de sessie
$gebruiker_id = $_SESSION['id'];

// Verwerk stemmen (like of dislike)
if (isset($_GET['action'], $_GET['post_id']) && in_array($_GET['action'], ['like', 'dislike'])) {
    $post_id = $_GET['post_id'];
    $actie = $_GET['action'];

    // Controleer of de gebruiker al eerder heeft gestemd op deze post
    if (!isset($_SESSION['stemgeschiedenis'])) {
        $_SESSION['stemgeschiedenis'] = [];
    }

    // Als de gebruiker al heeft gestemd op deze post
    if (isset($_SESSION['stemgeschiedenis'][$post_id])) {
        // Haal de vorige actie op
        $vorigeActie = $_SESSION['stemgeschiedenis'][$post_id];

        if ($vorigeActie === $actie) {
            // De gebruiker klikt opnieuw op dezelfde actie: verwijder de stem
            if ($actie === 'like') {
                $updatePost = $pdo->prepare("UPDATE posts SET likes = likes - 1 WHERE id = ?");
            } else {
                $updatePost = $pdo->prepare("UPDATE posts SET dislikes = dislikes - 1 WHERE id = ?");
            }
            $updatePost->execute([$post_id]);

            // Verwijder de actie uit de sessie
            unset($_SESSION['stemgeschiedenis'][$post_id]);
        } else {
            // De gebruiker verandert van actie (bijvoorbeeld van like naar dislike)
            if ($vorigeActie === 'like') {
                // Verwijder een like en voeg een dislike toe
                $pdo->prepare("UPDATE posts SET likes = likes - 1, dislikes = dislikes + 1 WHERE id = ?")
                    ->execute([$post_id]);
            } else {
                // Verwijder een dislike en voeg een like toe
                $pdo->prepare("UPDATE posts SET dislikes = dislikes - 1, likes = likes + 1 WHERE id = ?")
                    ->execute([$post_id]);
            }
            // Update de actie in de sessie
            $_SESSION['stemgeschiedenis'][$post_id] = $actie;
        }
    } else {
        // De gebruiker stemt voor de eerste keer op deze post
        if ($actie === 'like') {
            $updatePost = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
        } else {
            $updatePost = $pdo->prepare("UPDATE posts SET dislikes = dislikes + 1 WHERE id = ?");
        }
        $updatePost->execute([$post_id]);

        // Sla de actie op in de sessie
        $_SESSION['stemgeschiedenis'][$post_id] = $actie;
    }

    // Herlaad de pagina om de wijzigingen weer te geven
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Cars</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- Voeg Font Awesome toe voor duimpjes -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/vote-styles.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>Ratingcars.nl</h1>
        </div>
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="Paneel.php">Paneel</a>
            <a href="logout.php">Uitloggen</a>
        </nav>
    </header>

    <main>
        <section class="posts">
            <h2>Auto Posts</h2>
            <div class="post-lijst">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="post">
                            <h3><?= htmlspecialchars($post['titel']); ?></h3>
                            <?php if (!empty($post['afbeelding'])): ?>
                                <img src="../<?= htmlspecialchars($post['afbeelding']); ?>" alt="<?= htmlspecialchars($post['titel']); ?>" style="max-width: 100%; height: auto;">
                            <?php endif; ?>
                            <p>Gepost: <?= htmlspecialchars(date('d-m-Y H:i', strtotime($post['datum_gemaakt']))); ?></p>

                            <!-- Stemknoppen -->
                            <div class="vote-container">
                                <a href="?action=like&post_id=<?= $post['id']; ?>" class="vote-btn like">
                                    <i class="fa fa-thumbs-up"></i>
                                    <span><?= $post['likes']; ?></span>
                                </a>
                                <a href="?action=dislike&post_id=<?= $post['id']; ?>" class="vote-btn dislike">
                                    <i class="fa fa-thumbs-down"></i>
                                    <span><?= $post['dislikes']; ?></span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Er zijn nog geen posts beschikbaar.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
