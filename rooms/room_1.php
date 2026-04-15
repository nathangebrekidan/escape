<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 1");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Het schoollab</title>
  <link rel="stylesheet" href="../css/style.css">
  <script defer src="../js/app.js"></script>
</head>

<body class="backbody">

<div id="timer">Loading...</div>

<div id="room">
  <img id="door" src="../img/Door.png" alt="Deur" onclick="tryDoor()">
  <img id="table" src="../img/Tafel.png" alt="Table">
  <img id="table_two" src="../img/Tafel_2.png" alt="Tweede tafel">
  <img id="safe_closed" src="../img/Safe.png" alt="Kluis" onclick="trySafe()">
  <img id="safe_open" src="../img/Safe_open.png" alt="Kluis geopend" onclick="trySafe()">
  <img id="key" src="../img/Key.png" alt="Sleutel" onclick="pickUpKey()">
  <img id="key_card" src="../img/Key_Card.png" alt="Key Card" onclick="pickUpKeyCard()">
  <img id="keypad_locked" src="../img/Keypad_locked.png" alt="Keypad gesloten" onclick="tryKeypad()">
  <img id="keypad_unlocked" src="../img/Keypad_unlocked.png" alt="Keypad geopend" onclick="tryKeypad()">
  <img id="locked_box" src="../img/Locked_box.png" alt="Gesloten doos" onclick="tryBox()">
  <img id="opened_box" src="../img/Opened_box.png" alt="Geopende doos" onclick="tryBox()">
  <img id="lockpick" src="../img/Lockpick.png" alt="Lockpick" onclick="pickUpLockpick()">
  <img id="drawer_closed" src="../img/Drawer_closed.png" alt="Gesloten lade" onclick="tryDrawer()">
  <img id="drawer_open" src="../img/Drawer_open.png" alt="Geopende lade" onclick="tryDrawer()">
  <img id="lights" src="../img/Lights.png" alt="Licht" onclick="tryLight()">
  <img id="lights_two" src="../img/Lights_two.png" alt="Tweede licht" onclick="tryLight()">
  <img id="lightshadow" src="../img/Light_shadow.png" alt="Schaduw van het licht">
  <img id="lightshadow_two" src="../img/Light_shadow_two.png" alt="Schaduw van het tweede licht">
</div>

<p id="keypadmessage"></p>

  <div class="riddle-container">
    <?php foreach ($riddles as $index => $riddle) : ?>
    <div class="box box<?php echo $index + 1; ?>" onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>" data-riddle="<?php echo htmlspecialchars($riddle['riddle']); ?>"
      data-answer="<?php echo htmlspecialchars($riddle['answer']); ?>">
      Box <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <p id="feedback"></p>
  </section>

</body>

</html>