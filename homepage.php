<?php

?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <script defer src="../escape/js/app.js"></script>
    <title>Simlock: Escape the Simulation</title>
</head>

<body>
  <video autoplay muted loop id="bg-video">
    <source src="../escape/Vids/Simlock.mp4" type="video/mp4">
</video>

<header class="header">
    <div class="logo">Escape room</div>

    <nav class="menu">
        <a href="../escape/homepage.php">Menu</a>
        <a href="">Creators</a>
        <a href="">Review</a>
        <a href="">Account</a>
    </nav>
</header>

<main>

    <section class="intro">
        <h1 class="title">
            Simlock: Escape the simulation!
        </h1>

        <p class="subtitle">
            Find the clues, solve puzzles, escape before the timer runs out!
        </p>
    </section>

    <section class="content">

        <div class="image-box">
            <img src="../escape/img/Envelope photo.png" alt="Mysterious Envelope" id="clickImage">
        </div>

        <aside class="side-panel">
            <h2>Top scores</h2>

            <h3>Your top scores</h3>
            <ul>
                <li>-</li>
                <li>-</li>
                <li>-</li>
            </ul>

            <h3>Make your team</h3>
            <div>
                <button>Make team</button>
            </div>
        </aside>

    </section>

    <section class="buttons">
        <button>Difficulty</button>
        <button class="shake-btn">
            <a href="../escape/rooms/room_1.php">Start the escape</a>
        </button>
        <button>Clues</button>
    </section>

    <section class="popup" id="popup">
        <div class="popup-content">
            <span class="close-btn" id="closePopup">&times;</span>

            <h2 class="popup-text">Do not panic!</h2>

            <p class="popup-text">
                If you are reading this, it means that this letter has made it safely to the other side. I am writing this letter to you in the hope that it will help you escape from this simulation. Now I know this must sound crazy to you right now, but trust me, you are not in the real world. You are trapped in a simulation as an experiment, and the experience the higher-ups have planned for you are... sinister, to say the least.<br>
                <br>
                I know this must be overwhelming for you, but don't worry, I, reseacher 17, am here to guide you through this. Security is tight, and the simulation is designed to keep you trapped, but I have found a way to bypass the security and leave an escape open for you.<br>
                <br>
                There are special rooms I have set up for you in order to escape. The room you're standing in is one of them. Those rooms are not supposed to exist. I made them myself, and they are the only way out. However, these rooms have already been discovered before I had the change to hide them and the director has put 'security locks' on them. You'll have to solve puzzles in order to unlock them.<br>
                <br>
                You don't have time to lollygag, your body here in reality is deteriorating. Stay in the simulation for too long, and you will lose your mind to this experiment forever. You have to escape before that happens. You have ## minutes before this happens. Good luck and don't forget, the clock is ticking! <br>
                <br>
                Researcher 17
            </p>
        </div>
    </section>

    <section class="glitch" data-text="The stability is crumbling...">
        The stability is crumbling...
    </section>

</main>

<footer>
    &copy; 2026 Abenezer, Yannick & Nathan
</footer>

</body>
</html>