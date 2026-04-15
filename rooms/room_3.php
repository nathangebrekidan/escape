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
    $submitted_index = null;
    foreach ($r as $i => $v) {
        if (isset($_POST["a$i"])) {
            $submitted_index = $i;
            if (strtolower($_POST["a$i"]) == strtolower($v['answer'])) {
                $_SESSION['solved_' . $i] = true;
            } else {
                $err = "Fout antwoord voor raadsel " . chr(65 + $i);
            }
            break;
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
    <div class="vakje <?php echo isset($_SESSION['solved_0']) ? 'solved' : ''; ?>" <?php echo !isset($_SESSION['solved_0']) ? 'onclick="show(0)"' : ''; ?>>Raadsel A</div>
    <div class="vakje <?php echo isset($_SESSION['solved_0']) ? '' : 'hidden'; ?> <?php echo isset($_SESSION['solved_1']) ? 'solved' : ''; ?>" id="v2" <?php echo !isset($_SESSION['solved_1']) ? 'onclick="show(1)"' : ''; ?>>Raadsel B</div>
    <div class="vakje <?php echo isset($_SESSION['solved_1']) ? '' : 'hidden'; ?> <?php echo isset($_SESSION['solved_2']) ? 'solved' : ''; ?>" id="v3" <?php echo !isset($_SESSION['solved_2']) ? 'onclick="show(2)"' : ''; ?>>Raadsel C</div>
</div>

<form method="POST" id="f" class="hidden">
    <p id="vraag"></p>
    <input type="text" id="inp" name="a0">
    <button type="submit">Check</button>
</form>

<script>
let v = <?php echo json_encode($r); ?>;

function show(i){
    document.getElementById("f").classList.remove("hidden");
    document.getElementById("vraag").innerHTML = v[i].riddle;
    document.getElementById("inp").name = "a" + i;
    document.getElementById("inp").value = '';

    if(i == 0) document.getElementById("v2").classList.remove("hidden");
    if(i == 1) document.getElementById("v3").classList.remove("hidden");
}
</script>

<script src="../js/app.js"></script>

</body>
</html>