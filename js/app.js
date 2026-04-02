console.log("Script geladen");


function openModal(index) {
  let box = document.querySelector(`.box[data-index='${index}']`);
  let riddleText = box.dataset.riddle;
  let correctAnswer = box.dataset.answer;

  document.getElementById('riddle').innerText = riddleText;
  document.getElementById('modal').dataset.answer = correctAnswer;
  document.getElementById('answer').value = '';

  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
}


function closeModal() {
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';
  document.getElementById('feedback').innerText = '';
}


function checkAnswer() {
  let userAnswer = document.getElementById('answer').value.trim();
  let correctAnswer = document.getElementById('modal').dataset.answer;
  let feedback = document.getElementById('feedback');

  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';
    setTimeout(closeModal, 1000);
  } else {
    feedback.innerText = 'Fout, probeer opnieuw!';
    feedback.style.color = 'red';
  }
}


const popup = document.getElementById("popup");
const clickImage = document.getElementById("clickImage");
const closePopup = document.getElementById("closePopup");

if (popup && clickImage && closePopup) {
  clickImage.addEventListener("click", () => {
    popup.style.display = "flex";
  });

  closePopup.addEventListener("click", () => {
    popup.style.display = "none";
  });

  popup.addEventListener("click", (e) => {
    if (e.target === popup) {
      popup.style.display = "none";
    }
  });
}


const glitch = document.querySelector(".glitch");

setInterval(() => {
    glitch.style.transform = `translate(${Math.random()*4-2}px, ${Math.random()*4-2}px)`;
    setTimeout(() => {
        glitch.style.transform = "none";
    }, 100);
}, 2000);

const text = "The stability is crumbling...";
const glitchEl = document.querySelector(".glitch");

setInterval(() => {
    let broken = text.split("").map(letter => {
        return Math.random() > 0.9 
            ? String.fromCharCode(33 + Math.random()*94) 
            : letter;
    }).join("");

    glitchEl.innerText = broken;

    setTimeout(() => {
        glitchEl.innerText = text;
    }, 100);
}, 1500);

let timeLeft = 300;

function startTimer() {
  const timerElement = document.getElementById("timer");
  if (!timerElement) return;

  const interval = setInterval(() => {
    let minutes = Math.floor(timeLeft / 60);
    let seconds = timeLeft % 60;

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    timerElement.textContent = `${minutes}:${seconds}`;
    timeLeft--;

    if (timeLeft < 0) {
      clearInterval(interval);
      window.location.href = "/escape/rooms/lose_page.php";
    }
  }, 1000);
}

window.addEventListener("load", startTimer);