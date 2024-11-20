<?php require_once '../includes/fetch_autoposts.php'; ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating Cars</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>Ratingcars.nl</h1>
        </div>
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="Paneel.php">Paneel</a>
            <a href="/includes/logout.php">Uitloggen</a>

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
                            <!-- Wijziging hier: "Gemaakt op" naar "Gepost:" -->
                            <p>Gepost: <?= htmlspecialchars(date('d-m-Y H:i', strtotime($post['datum_gemaakt']))); ?></p>
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
