<?php
session_start();
require_once('../dbcon.php');

// Als je opnieuw start vanuit lose/win of handmatig refresh met restart-parameter.
if (isset($_GET['restart']) && $_GET['restart'] === '1') {
    unset($_SESSION['t3']);
    unset($_SESSION['solved_0'], $_SESSION['solved_1'], $_SESSION['solved_2']);
}

if (!isset($_SESSION['t3'])) {
    $_SESSION['t3'] = time();
}

$left = 90 - (time() - $_SESSION['t3']);
if ($left < 0) {
    $left = 0;
}

if ($left <= 0) {
    unset($_SESSION['t3']);
    header("Location: lose_page.php");
    exit;
}


$q = $db_connection->query("SELECT * FROM riddles WHERE roomId = 3");
$r = $q->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $index = isset($_POST['index']) ? intval($_POST['index']) : null;
    $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

    if ($index === null || !isset($r[$index])) {
        $err = "Ongeldig raadsel.";
    } elseif ($answer === '') {
        $err = "Vul eerst een antwoord in.";
    } else {
        $correct = strtolower($r[$index]['answer']);
        if (strtolower($answer) === $correct) {
            $_SESSION['solved_' . $index] = true;
        } else {
            $err = "Fout antwoord voor raadsel " . chr(65 + $index);
        }
    }

    // Check if all riddles are solved
    if (isset($_SESSION['solved_0']) && isset($_SESSION['solved_1']) && isset($_SESSION['solved_2'])) {
        unset($_SESSION['t3']);
        unset($_SESSION['solved_0'], $_SESSION['solved_1'], $_SESSION['solved_2']);
        header("Location: win_page.php");
        exit;
    }
}
?>
<html>
<head>
<link rel="stylesheet" href="../css/style.css?v=3">
<script>
let t = <?php echo $left; ?>;

function timer(){
    document.getElementById("tijd").innerHTML = t;
    if(t <= 0) location = "lose_page.php";
    t--;
    setTimeout(timer, 1000);
}
</script>
</head>

<body class="room-body" onload="timer()">

<header class="page-header">
  <h1>Escape Room 3</h1>
  <p>Laatste kamer, ultime kans. Een fout is kostbaar.</p>
</header>

<div class="riddle-form">
  <h2>Tijd: <span id="tijd"></span></h2>
  <?php if(isset($err)) echo "<p style='color:red;'>$err</p>"; ?>
</div>

<div class="container">
    <?php foreach ($r as $i => $v): ?>
        <div class="riddle-card <?php echo isset($_SESSION['solved_' . $i]) ? 'solved' : ''; ?>">
            <h3>Raadsel <?php echo chr(65 + $i); ?></h3>
            <p><?php echo htmlspecialchars($v['riddle']); ?></p>
            <?php if (isset($_SESSION['solved_' . $i])): ?>
                <p style="color: green; font-weight: bold;">Opgelost!</p>
            <?php else: ?>
            <form method="POST">
                <input type="hidden" name="index" value="<?php echo $i; ?>">
                <input type="text" name="answer" placeholder="Typ je antwoord" style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">
                <button type="submit" style="background:#221e3f; color:#fff; border:none; padding:10px 16px; border-radius:8px; cursor:pointer;">Check</button>
            </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script src="../js/app.js"></script>

</body>
</html>