<?php
session_start();
require_once('../dbcon.php');

// Maak de reviews-tabel aan als die nog niet bestaat.
$db_connection->exec("CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) DEFAULT 'Onbekend',
    room_id INT DEFAULT 3,
    rating INT NOT NULL,
    difficulty VARCHAR(50) NOT NULL,
    feedback TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$teamName = isset($_SESSION['team_name']) ? $_SESSION['team_name'] : 'Onbekend team';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating'] ?? 0);
    $difficulty = trim($_POST['difficulty'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');
    $roomId = intval($_POST['room_id'] ?? 3);

    if ($rating < 1 || $rating > 5) {
        $error = 'Kies een beoordeling tussen 1 en 5.';
    } elseif ($difficulty === '') {
        $error = 'Kies eerst een moeilijkheid.';
    } elseif ($feedback === '') {
        $error = 'Schrijf alsjeblieft een korte review.';
    } else {
        $stmt = $db_connection->prepare('INSERT INTO reviews (team_name, room_id, rating, difficulty, feedback) VALUES (:team_name, :room_id, :rating, :difficulty, :feedback)');
        $stmt->execute([
            ':team_name' => $teamName,
            ':room_id' => $roomId,
            ':rating' => $rating,
            ':difficulty' => $difficulty,
            ':feedback' => $feedback,
        ]);

        $success = 'Bedankt! Je review is opgeslagen.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review escape room</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
</head>
<body>
<header class="page-header">
    <h1>Review voor je escape room</h1>
    <p>Team: <?php echo htmlspecialchars($teamName); ?></p>
</header>

<div class="review-page">
    <div class="review-panel">
        <h2>Laat een review achter</h2>
        <?php if ($success): ?>
            <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="room_id" value="3">

            <label for="rating">Beoordeling (1 t/m 5)</label>
            <select id="rating" name="rating">
                <option value="0">-- Kies --</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>

            <label for="difficulty">Moeilijkheid</label>
            <select id="difficulty" name="difficulty">
                <option value="">-- Kies --</option>
                <option value="makkelijk">Makkelijk</option>
                <option value="normaal">Normaal</option>
                <option value="moeilijk">Moeilijk</option>
            </select>

            <label for="feedback">Review</label>
            <textarea id="feedback" name="feedback" rows="5"></textarea>

            <button type="submit">Review opslaan</button>
        </form>
        <p class="review-back-link"><a href="win_page.php">Terug naar winpagina</a></p>
    </div>
</div>

<footer style="text-align:center; margin-top:50px; color:#555;">&copy; 2026 Abenezer, Yannick & Nathan</footer>
</body>
</html>
