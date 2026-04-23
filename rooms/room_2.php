<?php
require_once(__DIR__ . '/../includes/db.php');

try {
  $stmt = $pdo->prepare('SELECT id, riddle, answer, book_number FROM riddles WHERE room_id = 2 ORDER BY book_number ASC');
  $stmt->execute();
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log('Database error: ' . $e->getMessage());
  $riddles = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room 2 - Library</title>
    <link rel="stylesheet" href="../css/room_2_style.css">
</head>
<body>

<div class="room-container">
    <div class="library-background">
        <div class="library">
            <div class="shelf">
                <div class="book book-1" onclick="openRiddle(1, '1')"></div>
                <div class="book book-2" onclick="openRiddle(2, '2')"></div>
                <div class="book book-3" onclick="openRiddle(3, '3')"></div>
                <div class="book book-4" onclick="openRiddle(4, '4')"></div>
                <div class="book book-5" onclick="openRiddle(5, '5')"></div>
            </div>
            <div class="shelf">
                <div class="book book-1" onclick="openRiddle(6, '6')"></div>
                <div class="book book-2" onclick="openRiddle(7, '7')"></div>
                <div class="book book-3" onclick="openRiddle(8, '8')"></div>
                <div class="book book-4" onclick="openRiddle(9, '9')"></div>
                <div class="book book-5" onclick="openRiddle(10, '10')"></div>
            </div>
            <div class="shelf">
                <div class="book book-1" onclick="openRiddle(11, '11')"></div>
                <div class="book book-2" onclick="openRiddle(12, '12')"></div>
                <div class="book book-3" onclick="openRiddle(13, '13')"></div>
                <div class="book book-4" onclick="openRiddle(14, '14')"></div>
                <div class="book book-5" onclick="openRiddle(15, '15')"></div>
            </div>
        </div>
        
        <div class="door"></div>
        
        <div class="timer-display">
            <span id="timerText">10:00</span>
        </div>

        <div class="note-paper" id="notePaper">
            <span class="close-letter" onclick="closeLetter()">&times;</span>
            <div id="letterContent" style="display: block;">
                <p class="note-title">📖 A Letter from the Librarian</p>
                <p class="note-text" style="font-size: 13px; color: #3a2a1a; line-height: 1.5; text-align: left;">
                    Dear Visitor,<br><br>
                    Welcome to our ancient library. The books on these shelves hold the keys to your freedom. Each book contains a riddle hidden within its pages.<br><br>
                    <strong>How to escape:</strong><br>
                    • Click on any colored book to reveal its riddle<br>
                    • Solve each riddle by typing your answer<br>
                    • Collect all the books' answers to unlock the door<br>
                    • The sequence of books is your guide to victory<br><br>
                    <em>Time is your enemy. You have 10 minutes.</em><br><br>
                    Good luck!<br>
                    - The Keeper of Secrets
                </p>
                <button class="letter-toggle" onclick="toggleNote()">See Book Sequence</button>
            </div>
            <div id="keyContent" style="display: none;">
                <p class="note-title">📚 Library Key</p>
                <p class="note-hint">Find books in order:</p>
                <div id="bookSequence" class="book-sequence"></div>
                <button class="letter-toggle" onclick="toggleNote()">Back to Letter</button>
            </div>
        </div>

        <div class="riddles-container" style="display: none;"></div>

        <div class="modal" id="riddleModal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeRiddle()">&times;</span>
                <h2>Riddle</h2>
                <p id="riddleText"></p>
                <input type="text" id="riddleAnswer" placeholder="Your answer..." onkeypress="if(event.key==='Enter') checkRiddleAnswer()">
                <button onclick="checkRiddleAnswer()">Submit</button>
                <p id="riddleFeedback" class="feedback"></p>
            </div>
        </div>

        <div class="overlay" id="overlay" onclick="closeRiddle()"></div>

        <div class="success-screen" id="successScreen">
            <h1>Door Unlocked!</h1>
            <p>You escaped the library!</p>
            <p id="timeScore"></p>
            <button onclick="redirectToRoom3()">Enter Room 3</button>
        </div>
    </div>
</div>

<script>
const riddles = <?php echo json_encode($riddles); ?>;
</script>
<script src="../js/room_2_script.js"></script>
</body>