console.log("Script geladen");

var modalSource = "";
var currentIndex = 0;

function openModal(index, source = "box") {
  currentIndex = index;
  modalSource = source;

  let boxes = document.querySelectorAll(".box");
  
  if (boxes[index]) {
    let riddleText = boxes[index].dataset.riddle;
    let correctAnswer = boxes[index].dataset.answer;

    document.getElementById('riddle').innerText = riddleText;
    document.getElementById('modal').dataset.answer = correctAnswer;
    document.getElementById('answer').value = '';
    document.getElementById('feedback').innerText = '';

    document.getElementById('overlay').style.display = 'block';
    document.getElementById('modal').style.display = 'block';
  } else {
    console.error("Geen riddle gevonden voor index: " + index);
  }
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

    setTimeout(() => {
      closeModal();

      if (modalSource === "drawer") {
        drawerClosed = false;
        document.getElementById("drawer_closed").style.visibility = "hidden";
        document.getElementById("drawer_open").style.visibility = "visible";
        document.getElementById("lockpick").style.visibility = "visible";
        showMessage("De lade is open! Er zit een lockpick in.", true);

      } else if (modalSource === "box") {
        boxLocked = false;
        document.getElementById("locked_box").style.visibility = "hidden";
        document.getElementById("opened_box").style.visibility = "visible";
        document.getElementById("key").style.visibility = "visible";
        showMessage("De doos is open! Er zit een sleutel in.", true);

      } else if (modalSource === "safe") {
        safeLocked = false;
        document.getElementById("safe_closed").style.visibility = "hidden";
        document.getElementById("safe_open").style.visibility = "visible";
        document.getElementById("key_card").style.visibility = "visible";
        showMessage("De kluis is open! Er zit een Key Card in.", true);
      }

    }, 1000);

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

function startTimer() {
  const timerElement = document.getElementById("timer");
  if (!timerElement) return;

  let endTime = sessionStorage.getItem("endTime");

  console.log("Stored endTime:", endTime);

  if (!endTime) {
    endTime = Date.now() + 300200;
    sessionStorage.setItem("endTime", endTime);
    console.log("Nieuwe endTime gezet:", endTime);
  } else {
    endTime = parseInt(endTime);
  }

  const interval = setInterval(() => {
    const timeLeft = Math.floor((endTime - Date.now()) / 1000);

    if (timeLeft <= 0) {
      clearInterval(interval);
      sessionStorage.removeItem("endTime");
      window.location.href = "../rooms/lose_page.php";
      return;
    }

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

window.addEventListener("pageshow", function (event) {
  if (event.persisted) {
    sessionStorage.removeItem("endTime");
    location.reload();
  }
});

var hasKeyCard = false;
var hasKey = false;
var hasLockpick = false;
var drawerClosed = true;
var boxLocked = true;
var keypadLocked = true;
var safeLocked = true;
var doorLocked = true;

function tryDoor() {
  if (!doorLocked) {
    window.location.href = "../rooms/room_2.php";
  } else if (hasKeyCard === "picked_up") {
    showMessage("Use the key card to unlock the door.");
  } else {
    showMessage("Looks like you need to unlock the keypad to open the door.");
  }
}

function showMessage(text) {
  const msg = document.getElementById("keypadmessage");
  msg.textContent = text;
  msg.classList.add("show");

  setTimeout(() => {
    msg.classList.remove("show");
  }, 2000);
}

function tryKeypad() {
  if (!keypadLocked) {
    showMessage("Keypad is already unlocked..");
    return;
  }

  if (hasKeyCard === "picked_up") {
    keypadLocked = false;
    doorLocked = false;

    document.getElementById("keypad_locked").style.visibility = "hidden";
    document.getElementById("keypad_unlocked").style.visibility = "visible";

    showMessage("Keypad unlocked! The door is now open.", true);
  } else if (hasKey === "picked_up") {
    showMessage("...why would a key work here?");
  } else {
    showMessage("The keypad is locked. You need a Key Card.");
  }
}

function pickUpKeyCard() {
  if (hasKeyCard === "picked_up") {
    showMessage("You have already picked up the Key Card.");
    return;
  }

  hasKeyCard = "picked_up";
  document.getElementById("key_card").style.visibility = "hidden";
  showMessage("You have picked up the Key Card!");
}

function tryDrawer() {
  if (!drawerClosed) {
    showMessage("The drawer is already open.");
    return;
  }
  openModal(0, "drawer");
}

function pickUpLockpick() {
  hasLockpick = true;

  document.getElementById("lockpick").style.visibility = "hidden";

  showMessage("You picked up the lockpick!");
}

function tryBox() {
  if (!boxLocked) {
    showMessage("The box is already open.");
    return;
  }

  if (!hasLockpick) {
    showMessage("You need something to open the box.");
    return;
  }
  openModal(1, "box");
}

function pickUpKey() {
 if (hasKey === "picked_up") {
    showMessage("You have already picked up the key.");
    return;
  }

  hasKey = "picked_up";
  document.getElementById("key").style.visibility = "hidden";
  showMessage("You picked up the key!");
}

function trySafe() {
  if (!safeLocked) {
    showMessage("The safe is already open.");
    return;
  }

  if (hasKey !== "picked_up") {
    if (hasLockpick) {
      showMessage("The lockpick is too small for this safe.");
    } else {
      showMessage("You need a key to open the safe.");
    }
    return;
  }

  openModal(2, "safe");
}

function tryLight() {
  showMessage("You look at the lights. It flickers for a moment, but nothing else happens.");
}

function tryNotes() {
  document.getElementById("notes-overlay").style.display = "block";
  document.getElementById("notes-modal").style.display = "block";
}

function closeNotes() {
  document.getElementById("notes-overlay").style.display = "none";
  document.getElementById("notes-modal").style.display = "none";
}

window.onload = function() {
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
  document.getElementById('riddle').innerText = "Welcome in Simlock! Riddle your way out.";
};

window.onload = function() {
  document.getElementById('introOverlay').style.display = 'block';
  document.getElementById('introModal').style.display = 'block';
};

function closeIntro() {
  document.getElementById('introOverlay').style.display = 'none';
  document.getElementById('introModal').style.display = 'none';
}

function tryStart() {
  showMessage("Je moet eerst een team aanmaken voor je kan beginnen.");
  return false;
}