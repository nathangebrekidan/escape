<?php
session_start();
require_once('../dbcon.php');

$success = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $riddle = isset($_POST['riddle']) ? trim($_POST['riddle']) : '';
    $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
    $hint = isset($_POST['hint']) ? trim($_POST['hint']) : '';
    $roomId = isset($_POST['roomId']) ? intval($_POST['roomId']) : 0;

    if ($riddle === '') {
        $err = "Vul een raadsel in.";
    } elseif ($answer === '') {
        $err = "Vul een antwoord in.";
    } elseif ($roomId < 1) {
        $err = "Kies een geldig room ID.";
    } else {
        $stmt = $db_connection->prepare("INSERT INTO riddles (riddle, answer, hint, roomId) VALUES (?, ?, ?, ?)");
        $stmt->execute([$riddle, $answer, $hint, $roomId]);
        $success = "Raadsel succesvol toegevoegd!";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Raadsel toevoegen</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
</head>
<body>
<header class="page-header">
    <h1>Raadsel toevoegen</h1>
    <p>Voeg een nieuw raadsel toe aan de escape room.</p>
</header>

<div class="riddle-form">
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if ($err): ?>
        <p style="color:red;"><?php echo $err; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Raadsel:</label><br>
        <textarea name="riddle" rows="4" style="width:100%;"></textarea><br><br>

        <label>Antwoord:</label>
        <input type="text" name="answer" style="width:100%;"><br><br>

        <label>Hint:</label>
        <input type="text" name="hint" style="width:100%;"><br><br>

        <label>Room ID:</label>
        <select name="roomId">
            <option value="">-- Kies --</option>
            <option value="1">Room 1</option>
            <option value="2">Room 2</option>
            <option value="3">Room 3</option>
        </select><br><br>

        <button type="submit" style="background:#221e3f;color:#fff;border:none;padding:10px 16px;border-radius:8px;cursor:pointer;">Toevoegen</button>
    </form>
</div>

<footer style="text-align:center;color:#999;padding:20px 0;">&copy; 2026 Abenezer, Yannick & Nathan</footer>
</body>
</html>