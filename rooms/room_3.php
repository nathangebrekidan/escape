<?php
session_start();
require_once('../dbcon.php');

// Als je opnieuw start vanuit lose/win of handmatig refresh met restart-parameter.
if (isset($_GET['restart']) && $_GET['restart'] === '1') {
    unset($_SESSION['t3']);
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
</div>

<div class="container">
    <div class="vakje" onclick="show(0)">Raadsel A</div>
    <div class="vakje hidden" id="v2" onclick="show(1)">Raadsel B</div>
    <div class="vakje hidden" id="v3" onclick="show(2)">Raadsel C</div>
</div>

<div id="f" class="hidden">
    <p id="vraag"></p>
    <input type="text" id="inp">
    <button onclick="checkAnswer()">Check</button>
    <p id="feedback"></p>
</div>

<script>
let v = <?php echo json_encode($r); ?>;
let current = 0;
let solved = [false, false, false];

function show(i){
    current = i;
    document.getElementById("f").classList.remove("hidden");
    document.getElementById("vraag").innerHTML = v[i].riddle;
    document.getElementById("inp").value = '';

    if(i == 0) document.getElementById("v2").classList.remove("hidden");
    if(i == 1) document.getElementById("v3").classList.remove("hidden");
}

function checkAnswer() {
    let userAnswer = document.getElementById('inp').value.trim();
    let feedback = document.getElementById('feedback');

    if (userAnswer.toLowerCase() === v[current].answer.toLowerCase()) {
        solved[current] = true;
        feedback.innerText = 'Correct!';
        feedback.style.color = 'green';
        document.getElementById("f").classList.add("hidden");
        // Mark as solved
        let vakje = document.querySelector(`.vakje[onclick*='show(${current})']`);
        vakje.classList.add("solved");
        vakje.onclick = null; // disable click

        if (solved.every(s => s)) {
            window.location.href = 'win_page.php';
        } else {
            // Auto show next if available
            let next = current + 1;
            if (next < 3 && !solved[next]) {
                show(next);
            }
        }
    } else {
        feedback.innerText = 'Fout, probeer opnieuw!';
        feedback.style.color = 'red';
    }
}
</script>

<script src="../js/app.js"></script>

</body>
</html>