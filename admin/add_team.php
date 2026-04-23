<?php
session_start();
require_once('../dbcon.php');

$success = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_name = isset($_POST['team_name']) ? trim($_POST['team_name']) : '';
    $member1 = isset($_POST['member1']) ? trim($_POST['member1']) : '';
    $member2 = isset($_POST['member2']) ? trim($_POST['member2']) : '';
    $member3 = isset($_POST['member3']) ? trim($_POST['member3']) : '';

    if ($team_name === '') {
        $err = "Vul een teamnaam in.";
    } elseif ($member1 === '' || $member2 === '') {
        $err = "Vul minimaal 2 teamleden in.";
    } else {
        $stmt = $db_connection->prepare("INSERT INTO teams (team_name, member1, member2, member3) VALUES (?, ?, ?, ?)");
        $stmt->execute([$team_name, $member1, $member2, $member3]);
        $success = "Team succesvol aangemaakt!";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Team aanmaken</title>
    <link rel="stylesheet" href="../css/style.css?v=4">
</head>
<body>
<header class="page-header">
    <h1>Team aanmaken</h1>
    <p>Maak een nieuw team aan voor de escape room.</p>
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
        <input type="text" name="team_name" style="width:100%;"><br><br>

        <label>Teamlid 1:</label>
        <input type="text" name="member1" style="width:100%;"><br><br>

        <label>Teamlid 2:</label>
        <input type="text" name="member2" style="width:100%;"><br><br>

        <label>Teamlid 3 (optioneel):</label>
        <input type="text" name="member3" style="width:100%;"><br><br>

        <button type="submit" style="background:#221e3f;color:#fff;border:none;padding:10px 16px;border-radius:8px;cursor:pointer;">Aanmaken</button>
    </form>
</div>

<footer style="text-align:center;color:#999;padding:20px 0;">&copy; 2026 Abenezer, Yannick & Nathan</footer>
</body>
</html>
