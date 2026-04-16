<?php
session_start();
require_once('../dbcon.php');

// Zorg dat de tabel bestaat, zodat de adminpagina niet faalt op een lege database.
$db_connection->exec("CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) DEFAULT 'Onbekend',
    room_id INT DEFAULT 3,
    rating INT NOT NULL,
    difficulty VARCHAR(50) NOT NULL,
    feedback TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $db_connection->prepare('SELECT * FROM reviews ORDER BY created_at DESC');
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht van reviews</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
</head>
<body>
<header class="page-header">
    <h1>Overzicht van reviews</h1>
    <p>Hier zie je alle reviews die door teams zijn achtergelaten.</p>
</header>

<main style="width:95%; max-width:1100px; margin: 0 auto 50px;">
    <?php if (count($reviews) === 0): ?>
        <p>Er zijn nog geen reviews toegevoegd.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Team</th>
                    <th>Room</th>
                    <th>Rating</th>
                    <th>Moeilijkheid</th>
                    <th>Review</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['id']); ?></td>
                        <td><?php echo htmlspecialchars($review['team_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['room_id']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?> / 5</td>
                        <td><?php echo htmlspecialchars($review['difficulty']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($review['feedback'])); ?></td>
                        <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<footer style="text-align:center; color:#999; padding:20px 0;">&copy; 2026 Abenezer, Yannick & Nathan</footer>
</body>
</html>
