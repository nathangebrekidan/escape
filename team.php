<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Teams aanmaken</title>
  <link rel="stylesheet" href="../escape/css/style.css">
</head>

<body class="teambody">

<nav class="navigatie">
  <a class="lego" href="../escape/homepage.php">Simlock</a>
  <div class="nav-links">
    <a href="../escape/homepage.php">Menu</a>
    <a href="#">Creators</a>
    <a href="#">Review</a>
  </div>
  <button class="nav-account btni">Account</button>
</nav>

<main class="holder">
  <div class="page-header">
    <h1>Team aanmaken</h1>
    <p class="subtitle">Maak een team en nodig leden uit.</p>
  </div>

  <div class="grid">

    <div class="card">
      <div class="lock-icon">🔒</div>

      <div class="field">
        <label for="teamnaam">Teamnaam</label>
        <input type="text" id="teamnaam" placeholder="Bijv. De Twins"/>
      </div>

      <div class="field">
        <label>Teamcode</label>
        <input type="text" id="teamcode-input" placeholder="Teamcode mag maar 6 tekens lang zijn"
          style="cursor:default; color: var(--muted);"/>
        <div class="code-display" id="code-display"></div>
      </div>

      <div class="field">
        <label for="invite">Leden uitnodigen</label>
        <div class="invite-row">
          <input type="email" id="invite" placeholder="email@voorbeeld.nl"/>
          <button class="btni btn-ghost" onclick="addMember()">Toevoegen</button>
        </div>
        <div class="tags" id="tags"></div>
      </div>
    </div>

    <div class="card" style="display:flex; flex-direction:column; justify-content:center;">
      <div class="card-title">Join team</div>
      <label for="join-code">Voer teamcode in</label>
      <input type="text" id="join-code" placeholder="Bijv. XK-4829" style="text-transform:uppercase; letter-spacing:.15em;"/>
      <button class="btni btn-join" onclick="joinTeam()">Join</button>
    </div>

  </div>

  <div class="actions">
    <button class="btni btn-secondary" onclick="cancelForm()">Annuleren</button>
    <button class="btni btn-primary" onclick="createTeam()">Maak team</button>
  </div>
</main>

<div id="toast"></div>

</body>
</html>
