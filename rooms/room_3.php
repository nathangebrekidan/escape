php<?php
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
if ($left < 0) $left = 0;

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
        $err = "Ongeldig raadsel, probeer het opnieuw.";
    } elseif ($answer === '') {
        $err = "Vul eerst een antwoord in, de klok tikt!";
    } else {
        $correct = $found['row']['answer'];
        $normalizedAnswer = mb_strtolower(trim(preg_replace('/\s+/', '', $answer)), 'UTF-8');
        $normalizedCorrect = mb_strtolower(trim(preg_replace('/\s+/', '', $correct)), 'UTF-8');

        if ($normalizedAnswer === $normalizedCorrect) {
            $_SESSION['solved'][$found['row']['id']] = true;

            $allSolved = true;
            foreach ($r as $row) {
                if (!isset($_SESSION['solved'][$row['id']])) { $allSolved = false; break; }
            }
            if ($allSolved) {
                unset($_SESSION['t3']); unset($_SESSION['solved']);
                header("Location: win_page.php"); exit;
            }
            header("Location: room_3.php"); exit;
        } else {
            // Straf: 30 seconden aftrekken
            $_SESSION['t3'] -= 30;
            $err = "Fout antwoord voor raadsel " . chr(65 + $found['index']) . " — Rotterdam vergeeft niet! -30 seconden!";
        }
    }

    $allSolved = true;
    foreach ($r as $row) {
        if (!isset($_SESSION['solved'][$row['id']])) { $allSolved = false; break; }
    }
    if ($allSolved) {
        unset($_SESSION['t3']); unset($_SESSION['solved']);
        header("Location: win_page.php"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../css/style.css?v=3">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Creepster&family=Oswald:wght@400;700&display=swap');

  body.room-body {
    background: #0a0a0a;
    color: #e0e0e0;
    font-family: 'Oswald', sans-serif;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
  }

  /* Regen animatie achtergrond */
  body.room-body::before {
    content: '';
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: 
      repeating-linear-gradient(
        to bottom,
        transparent 0px,
        transparent 4px,
        rgba(0,100,200,0.03) 4px,
        rgba(0,100,200,0.03) 5px
      );
    pointer-events: none;
    z-index: 0;
    animation: regen 0.3s linear infinite;
  }

  @keyframes regen {
    0% { background-position: 0 0; }
    100% { background-position: 0 20px; }
  }

  .page-header {
    background: linear-gradient(135deg, #0d0d0d, #1a0a0a);
    border-bottom: 3px solid #cc0000;
    padding: 30px 20px;
    text-align: center;
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 30px rgba(200,0,0,0.4);
  }

  .page-header h1 {
    font-family: 'Creepster', cursive;
    font-size: 3em;
    color: #cc0000;
    text-shadow: 0 0 20px rgba(200,0,0,0.8), 0 0 40px rgba(200,0,0,0.4);
    margin: 0;
    letter-spacing: 4px;
    animation: flicker 3s infinite;
  }

  @keyframes flicker {
    0%, 95%, 100% { opacity: 1; }
    96% { opacity: 0.4; }
    97% { opacity: 1; }
    98% { opacity: 0.2; }
    99% { opacity: 1; }
  }

  .page-header p {
    color: #888;
    font-size: 1em;
    margin-top: 8px;
    letter-spacing: 2px;
    text-transform: uppercase;
  }

  /* Timer */
  .timer-box {
    text-align: center;
    padding: 20px;
    position: relative;
    z-index: 1;
  }

  .timer-box h2 {
    font-size: 2.5em;
    color: #fff;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 3px;
  }

  #tijd {
    color: #cc0000;
    font-size: 1.2em;
    text-shadow: 0 0 10px rgba(200,0,0,0.8);
    animation: pulsTijd 1s infinite;
  }

  @keyframes pulsTijd {
    0%, 100% { text-shadow: 0 0 10px rgba(200,0,0,0.8); }
    50% { text-shadow: 0 0 25px rgba(200,0,0,1), 0 0 50px rgba(200,0,0,0.5); }
  }

  .foutmelding {
    background: rgba(200,0,0,0.15);
    border: 1px solid #cc0000;
    color: #ff4444;
    padding: 12px 20px;
    border-radius: 8px;
    text-align: center;
    max-width: 600px;
    margin: 0 auto 20px;
    font-weight: bold;
    letter-spacing: 1px;
    animation: schudden 0.4s ease;
  }

  @keyframes schudden {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
  }

  /* Raadsel cards */
  .container {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    justify-content: center;
    padding: 30px 20px;
    position: relative;
    z-index: 1;
  }

  .riddle-card {
    background: linear-gradient(145deg, #111, #1a1a1a);
    border: 1px solid #333;
    border-radius: 12px;
    padding: 28px;
    width: 320px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.05);
    transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
    position: relative;
    overflow: hidden;
  }

  .riddle-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 3px;
    background: linear-gradient(90deg, transparent, #cc0000, transparent);
  }

  .riddle-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 48px rgba(200,0,0,0.2), 0 8px 32px rgba(0,0,0,0.6);
    border-color: #cc0000;
  }

  .riddle-card.solved {
    border-color: #00cc44;
    box-shadow: 0 8px 32px rgba(0,200,68,0.15);
  }

  .riddle-card.solved::before {
    background: linear-gradient(90deg, transparent, #00cc44, transparent);
  }

  .riddle-card h3 {
    color: #cc0000;
    font-size: 1.1em;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin: 0 0 14px;
  }

  .riddle-card.solved h3 {
    color: #00cc44;
  }

  .riddle-card p {
    color: #ccc;
    line-height: 1.6;
    margin-bottom: 16px;
    font-size: 0.95em;
  }

  /* Verborgen antwoordveld — verschijnt pas na klik */
  .toon-formulier-btn {
    background: transparent;
    border: 1px solid #cc0000;
    color: #cc0000;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Oswald', sans-serif;
    font-size: 0.95em;
    letter-spacing: 2px;
    text-transform: uppercase;
    width: 100%;
    transition: background 0.2s, color 0.2s;
  }

  .toon-formulier-btn:hover {
    background: #cc0000;
    color: #fff;
  }

  .antwoord-formulier {
    display: none;
    margin-top: 14px;
    animation: inschuiven 0.4s ease;
  }

  @keyframes inschuiven {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .antwoord-formulier input[type=text] {
    width: 100%;
    padding: 10px;
    background: #0d0d0d;
    border: 1px solid #444;
    border-radius: 6px;
    color: #fff;
    font-family: 'Oswald', sans-serif;
    font-size: 1em;
    margin-bottom: 10px;
    box-sizing: border-box;
    transition: border-color 0.2s;
  }

  .antwoord-formulier input[type=text]:focus {
    outline: none;
    border-color: #cc0000;
    box-shadow: 0 0 8px rgba(200,0,0,0.3);
  }

  .antwoord-formulier button {
    background: #cc0000;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Oswald', sans-serif;
    font-size: 1em;
    letter-spacing: 2px;
    text-transform: uppercase;
    width: 100%;
    transition: background 0.2s, box-shadow 0.2s;
  }

  .antwoord-formulier button:hover {
    background: #ff1a1a;
    box-shadow: 0 0 15px rgba(200,0,0,0.5);
  }

  .opgelost-tekst {
    color: #00cc44;
    font-weight: bold;
    font-size: 1em;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-shadow: 0 0 10px rgba(0,200,68,0.5);
  }

  /* Voortgangsbalk */
  .voortgang-wrapper {
    text-align: center;
    padding: 10px 20px 0;
    position: relative;
    z-index: 1;
  }

  .voortgang-balk {
    background: #1a1a1a;
    border-radius: 20px;
    height: 8px;
    max-width: 500px;
    margin: 8px auto;
    overflow: hidden;
    border: 1px solid #333;
  }

  .voortgang-vulling {
    height: 100%;
    background: linear-gradient(90deg, #cc0000, #ff4444);
    border-radius: 20px;
    transition: width 0.5s ease;
    box-shadow: 0 0 10px rgba(200,0,0,0.5);
  }

  .voortgang-tekst {
    color: #666;
    font-size: 0.8em;
    letter-spacing: 2px;
    text-transform: uppercase;
  }
</style>

<script>
let t = <?php echo $left; ?>;

function timer(){
    let minuten = Math.floor(t / 60);
    let seconden = t % 60;
    let display = minuten + ":" + (seconden < 10 ? "0" : "") + seconden;
    document.getElementById("tijd").innerHTML = display;

    // Timer wordt rood als minder dan 60 seconden
    if (t <= 60) {
        document.getElementById("tijd").style.color = "#ff0000";
        document.getElementById("tijd").style.fontSize = "1.5em";
    }

    if(t <= 0) location = "lose_page.php";
    t--;
    setTimeout(timer, 1000);
}

function toonFormulier(id) {
    let form = document.getElementById('form-' + id);
    let btn = document.getElementById('btn-' + id);
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        btn.textContent = '🔒 Verberg invoer';
    } else {
        form.style.display = 'none';
        btn.textContent = '🔓 Antwoord invoeren';
    }
}
</script>
</head>

<body class="room-body" onload="timer()">

<header class="page-header">
  <h1>🔒 Eindstation Rotterdam</h1>
  <p>Je zit opgesloten in het hart van de Maasstad — kraak de codes of Rotterdam slikt je op</p>
</header>

<div class="timer-box">
  <h2>⏱ Tijd over: <span id="tijd"></span></h2>
</div>

<?php
$opgelost = 0;
foreach ($r as $row) { if (isset($_SESSION['solved'][$row['id']])) $opgelost++; }
$totaal = count($r);
$procent = $totaal > 0 ? round(($opgelost / $totaal) * 100) : 0;
?>

<div class="voortgang-wrapper">
  <p class="voortgang-tekst"><?php echo $opgelost; ?> / <?php echo $totaal; ?> raadsels opgelost</p>
  <div class="voortgang-balk">
    <div class="voortgang-vulling" style="width: <?php echo $procent; ?>%;"></div>
  </div>
</div>

<?php if(isset($err)): ?>
<div style="text-align:center; padding: 0 20px; position:relative; z-index:1;">
  <div class="foutmelding">⚠️ <?php echo $err; ?></div>
</div>
<?php endif; ?>

<div class="container">
    <?php foreach ($r as $i => $v): ?>
        <div class="riddle-card <?php echo isset($_SESSION['solved'][$v['id']]) ? 'solved' : ''; ?>">
            <h3>🧩 Raadsel <?php echo chr(65 + $i); ?></h3>
            <p><?php echo htmlspecialchars($v['riddle']); ?></p>

            <?php if (isset($_SESSION['solved'][$v['id']])): ?>
                <p class="opgelost-tekst">✅ Gekraakt!</p>
            <?php else: ?>
                <button class="toon-formulier-btn" id="btn-<?php echo $v['id']; ?>" onclick="toonFormulier(<?php echo $v['id']; ?>)">
                    🔓 Antwoord invoeren
                </button>
                <div class="antwoord-formulier" id="form-<?php echo $v['id']; ?>">
                    <form method="POST">
                        <input type="hidden" name="riddle_id" value="<?php echo $v['id']; ?>">
                        <input type="text" name="answer" placeholder="Typ je antwoord... de klok tikt">
                        <button type="submit">🔐 Controleer</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script src="../js/app.js"></script>

</body>
</html>