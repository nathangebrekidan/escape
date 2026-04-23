<?php
session_start();
require_once('../dbcon.php');

$success = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = isset($_POST['team_name']) ? trim($_POST['team_name']) : 'Onbekend';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $difficulty = isset($_POST['difficulty']) ? trim($_POST['difficulty']) : '';
    $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';

    if ($rating < 1 || $rating > 5) {
        $err = "Kies een rating tussen 1 en 5.";
    } elseif ($difficulty === '') {
        $err = "Kies een moeilijkheidsgraad.";
    } elseif ($feedback === '') {
        $err = "Vul een review in.";
    } else {
        $stmt = $db_connection->prepare("INSERT INTO reviews (team_name, room_id, rating, difficulty, feedback) VALUES (?, 3, ?, ?, ?)");
        $stmt->execute([$team_name, $rating, $difficulty, $feedback]);
        $success = "Review succesvol toegevoegd!";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Review toevoegen</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
</head>
<body>
<header class="page-header">
    <h1>Review toevoegen</h1>
    <p>Laat een review achter over de escape room.</p>
</header>

<div class="riddle-form">
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if ($err): ?>
        <p style="color:red;"><?php echo $err; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Teamnaam:</label>
        <input type="text" name="team_name" placeholder="Jouw teamnaam"><br><br>

        <label>Rating (1-5):</label>
        <select name="rating">
            <option value="">-- Kies --</option>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select><br><br>

        <label>Moeilijkheid:</label>
        <select name="difficulty">
            <option value="">-- Kies --</option>
            <option value="Makkelijk">Makkelijk</option>
            <option value="Gemiddeld">Gemiddeld</option>
            <option value="Moeilijk">Moeilijk</option>
        </select><br><br>

        <label>Review:</label><br>
        <textarea name="feedback" rows="5" style="width:100%;"></textarea><br><br>

        <button type="submit" style="background:#221e3f;color:#fff;border:none;padding:10px 16px;border-radius:8px;cursor:pointer;">Verstuur</button>
    </form>
</div>

<footer style="text-align:center;color:#999;padding:20px 0;">&copy; 2026 Abenezer, Yannick & Nathan</footer>
</body>
</html>