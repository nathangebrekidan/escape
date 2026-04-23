<?php
session_start();
require_once('../dbcon.php');

if (!isset($_SESSION['solved']) || !is_array($_SESSION['solved'])) {
    $_SESSION['solved'] = [];
}

foreach (array_keys($_SESSION) as $key) {
    if (strpos($key, 'solved_') === 0 && $key !== 'solved') {
        $oldId = substr($key, 7);
        if ($oldId !== '' && $_SESSION[$key]) {
            $_SESSION['solved'][$oldId] = true;
        }
        unset($_SESSION[$key]);
    }
}

if (isset($_GET['restart']) && $_GET['restart'] === '1') {
    unset($_SESSION['t3']);
    unset($_SESSION['solved']);
}

if (!isset($_SESSION['t3'])) {
    $_SESSION['t3'] = time();
}

$left = 300 - (time() - $_SESSION['t3']);
if ($left < 0) {
    $left = 0;
}

if ($left <= 0) {
    unset($_SESSION['t3']);
    header("Location: lose_page.php");
    exit;
}

$q = $db_connection->query("SELECT * FROM riddles WHERE roomId = 3 ORDER BY id ASC");
$r = $q->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $riddleId = isset($_POST['riddle_id']) ? intval($_POST['riddle_id']) : null;
    $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';

    $found = null;
    foreach ($r as $i => $v) {
        if (intval($v['id']) === $riddleId) {
            $found = ['index' => $i, 'row' => $v];
            break;
        }
    }

    if (!$found) {
        $err = "Ongeldig raadsel.";
    } elseif ($answer === '') {
        $err = "Vul eerst een antwoord in.";
    } else {
        $correct = $found['row']['answer'];
        $normalizedAnswer = mb_strtolower(trim(preg_replace('/\s+/', '', $answer)), 'UTF-8');
        $normalizedCorrect = mb_strtolower(trim(preg_replace('/\s+/', '', $correct)), 'UTF-8');

        if ($normalizedAnswer === $normalizedCorrect) {
            $_SESSION['solved'][$found['row']['id']] = true;

            $allSolved = true;
            foreach ($r as $row) {
                if (!isset($_SESSION['solved'][$row['id']])) {
                    $allSolved = false;
                    break;
                }
            }

            if ($allSolved) {
                unset($_SESSION['t3']);
                unset($_SESSION['solved']);
                header("Location: win_page.php");
                exit;
            }

            header("Location: room_3.php");
            exit;
        } else {
            $err = "Fout antwoord voor raadsel " . chr(65 + $found['index']);
        }
    }

    $allSolved = true;
    foreach ($r as $row) {
        if (!isset($_SESSION['solved'][$row['id']])) {
            $allSolved = false;
            break;
        }
    }

    if ($allSolved) {
        unset($_SESSION['t3']);
        unset($_SESSION['solved']);
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
        <div class="riddle-card <?php echo isset($_SESSION['solved'][$v['id']]) ? 'solved' : ''; ?>">
            <h3>Raadsel <?php echo chr(65 + $i); ?></h3>
            <p><?php echo htmlspecialchars($v['riddle']); ?></p>
            <?php if (isset($_SESSION['solved'][$v['id']])): ?>
                <p style="color: green; font-weight: bold;">Opgelost!</p>
            <?php else: ?>
            <form method="POST">
                <input type="hidden" name="riddle_id" value="<?php echo $v['id']; ?>">
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