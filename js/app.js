console.log("Script geladen");
// Deze functie opent de modal en toont de vraag
function openModal(index) {
  // Zoek het element met de class 'box' en het bijbehorende data-index
  let box = document.querySelector(`.box[data-index='${index}']`);

  // Haal de vraag en het juiste antwoord uit de dataset van de box
  let riddleText = box.dataset.riddle;
  let correctAnswer = box.dataset.answer;

  // Zet de vraagtekst in het modalvenster
  document.getElementById('riddle').innerText = riddleText;

  // Zet het correcte antwoord in de modal, zodat we het later kunnen vergelijken
  document.getElementById('modal').dataset.answer = correctAnswer;

  // Maak het antwoordveld leeg
  document.getElementById('answer').value = '';

  // Toon de overlay en de modal door de display-stijl te veranderen naar 'block'
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
}

// Deze functie sluit de modal en de overlay
function closeModal() {
  // Zet de overlay en modal weer op 'none' zodat ze niet meer zichtbaar zijn
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';

  // Maak de feedback tekst leeg
  document.getElementById('feedback').innerText = '';
}

// Deze functie controleert of het ingevoerde antwoord correct is
function checkAnswer() {
  // Haal het antwoord van de gebruiker op uit het invoerveld en verwijder onnodige spaties
  let userAnswer = document.getElementById('answer').value.trim();

  // Haal het juiste antwoord op uit de modal
  let correctAnswer = document.getElementById('modal').dataset.answer;

  // Haal het feedback element op om de gebruiker te informeren
  let feedback = document.getElementById('feedback');

  // Vergelijk het antwoord van de gebruiker met het juiste antwoord (hoofdlettergevoeligheid negeren)
  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    // Als het antwoord juist is, geef positieve feedback
    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';

    // Sluit de modal na 1 seconde
    setTimeout(closeModal, 1000);
  } else {
    // Als het antwoord fout is, geef negatieve feedback
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

let timeLeft = 50000000;

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
      window.location.href = "../rooms/lose_page.php";
    }
  }, 1000);
}

window.addEventListener("load", startTimer);