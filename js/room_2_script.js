// Room 2 
// Ik heb did hier gedaan als er iets fout gaat

let currentRiddleId = null;
let solvedRiddles = new Set();
let timeRemaining = 600; // 10 minuutjes
let gameActive = true;
let correctSequence = [];

document.addEventListener('DOMContentLoaded', () => {
    initializeGame();
    startTimer();
    generateBookSequence();
});

function initializeGame() {
    if (riddles.length === 0) {
        console.error('No riddles loaded');
        return;
    }

    correctSequence = riddles.map(r => parseInt(r.book_number)).sort((a, b) => a - b);
}

function generateBookSequence() {
    const container = document.getElementById('bookSequence');
    correctSequence.forEach(num => {
        const span = document.createElement('span');
        span.textContent = `Book ${num}`;
        span.id = `seq-${num}`;
        container.appendChild(span);
    });
}

function startTimer() {
    const timerInterval = setInterval(() => {
        if (!gameActive) {
            clearInterval(timerInterval);
            return;
        }

        timeRemaining--;
        updateTimerDisplay();

        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            endGame(false);
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    const timerText = document.getElementById('timerText');
    timerText.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

    if (timeRemaining <= 60) {
        timerText.parentElement.classList.add('warning');
    }
}

function openRiddle(riddleId) {
    if (solvedRiddles.has(riddleId) || !gameActive) return;

    currentRiddleId = riddleId;
    const riddle = riddles.find(r => parseInt(r.id) === parseInt(riddleId));
    
    if (!riddle) {
        console.error('Riddle not found:', riddleId);
        return;
    }

    document.getElementById('riddleText').textContent = riddle.riddle;
    document.getElementById('riddleAnswer').value = '';
    document.getElementById('riddleFeedback').textContent = '';

    document.getElementById('riddleModal').classList.add('open');
    document.getElementById('overlay').classList.add('active');
}

function closeRiddle() {
    document.getElementById('riddleModal').classList.remove('open');
    document.getElementById('overlay').classList.remove('active');
    currentRiddleId = null;
}

function checkRiddleAnswer() {
    if (!currentRiddleId) return;

    const riddle = riddles.find(r => parseInt(r.id) === parseInt(currentRiddleId));
    const userAnswer = document.getElementById('riddleAnswer').value.trim().toLowerCase();
    const correctAnswer = riddle.answer.toLowerCase();

    const feedbackElement = document.getElementById('riddleFeedback');

    if (userAnswer === correctAnswer) {
        solvedRiddles.add(currentRiddleId);
        feedbackElement.textContent = '✓ Correct!';
        feedbackElement.classList.add('correct');
        feedbackElement.classList.remove('incorrect');

        const riddleBox = document.querySelector(`[data-riddle-id="${currentRiddleId}"]`);
        riddleBox.classList.add('solved');

        document.getElementById(`seq-${riddle.book_number}`).classList.add('found');

        setTimeout(() => {
            closeRiddle();
            checkWinCondition();
        }, 1000);
    } else {
        feedbackElement.textContent = '✗ Incorrect. Try again!';
        feedbackElement.classList.add('incorrect');
        feedbackElement.classList.remove('correct');
    }
}

function checkWinCondition() {
    if (solvedRiddles.size === riddles.length) {
        endGame(true);
    }
}

function endGame(won) {
    gameActive = false;

    if (won) {
        triggerAlarm();
        setTimeout(() => {
            showSuccessScreen();
        }, 2000);
    } else {
        showGameOver();
    }
}

function triggerAlarm() {
    const door = document.querySelector('.door');
    door.classList.add('alarm-animation');
    door.classList.add('open');

    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.frequency.value = 800;
    oscillator.type = 'sine';
    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

function showSuccessScreen() {
    const successScreen = document.getElementById('successScreen');
    const timeScore = document.getElementById('timeScore');

    const minutes = Math.floor((600 - timeRemaining) / 60);
    const seconds = (600 - timeRemaining) % 60;
    timeScore.textContent = `Time: ${minutes}:${seconds.toString().padStart(2, '0')}`;

    successScreen.classList.add('show');
}

function redirectToRoom3() {
    window.location.href = '../rooms/room_3.php';
}

function showGameOver() {
    alert('Time is up! You did not escape the library.');
    window.location.href = '../rooms/lose_page.php';
}

function toggleNote() {
    const letterContent = document.getElementById('letterContent');
    const keyContent = document.getElementById('keyContent');
    
    if (letterContent.style.display === 'block') {
        letterContent.style.display = 'none';
        keyContent.style.display = 'block';
    } else {
        letterContent.style.display = 'block';
        keyContent.style.display = 'none';
    }
}

function closeLetter() {
    const notePaper = document.getElementById('notePaper');
    notePaper.style.display = 'none';
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeRiddle();
    }
});
