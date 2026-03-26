<?php
session_start();
require_once('../dbcon.php');


if (!isset($_SESSION['t3'])) $_SESSION['t3'] = time();
$left = 90 - (time() - $_SESSION['t3']);
if ($left <= 0) header("Location: lose.php");


$q = $db_connection->query("SELECT * FROM riddles WHERE roomId = 3");
$r = $q->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ok = true;
    foreach ($r as $i => $v) {
        if (strtolower($_POST["a$i"]) != strtolower($v['answer'])) $ok = false;
    }
    if ($ok) {
        unset($_SESSION['t3']);
        header("Location: win.php");
    } else $err = "Fout antwoord.";
}
?>
<html>
<head>
<link rel="stylesheet" href="../css/style.css">
<script>
let t = <?php echo $left; ?>;
function timer(){
    document.getElementById("tijd").innerHTML = t;
    if(t<=0) location="lose.php";
    t--; setTimeout(timer,1000);
}
</script>
</head>

<body onload="timer()">

<h2>Tijd: <span id="tijd"></span></h2>
<?php if(isset($err)) echo "<p style='color:red;'>$err</p>"; ?>

<div class="container">
    <div class="box" onclick="show(0)">Puzzel 1</div>
    <div class="box hidden" id="b2" onclick="show(1)">Puzzel 2</div>
    <div class="box hidden" id="b3" onclick="show(2)">Puzzel 3</div>
</div>

<form method="POST" id="f" class="hidden">
    <p id="vraag"></p>
    <input type="text" id="inp" name="a0">
    <button>Check</button>
</form>

<script>
let v = <?php echo json_encode($r); ?>;

function show(i){
    document.getElementById("f").classList.remove("hidden");
    document.getElementById("vraag").innerHTML = v[i].riddle;
    document.getElementById("inp").name = "a"+i;

    if(i==0) document.getElementById("b2").classList.remove("hidden");
    if(i==1) document.getElementById("b3").classList.remove("hidden");
}
</script>
    <footer> &copy; 2026 Abenezer, Yannick & Nathan</footer>

  <script src="../js/app.js"></script>

</body>
</html>