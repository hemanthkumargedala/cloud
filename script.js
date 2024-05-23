let currentQuestion = 0;
const questions = [
    "Step into a world of culinary delights with our restaurant recommendation chatbot!",
    "What is your location?",
    "What type of cuisine are you in the mood for? (e.g., Indian, Chinese, etc.)",
    "Are you looking for vegetarian or non-vegetarian options?"
];
const answers = [];
const avatarImages = [
    "avatar-welcome.png",
    "avatar-location.png",
    "avatar-cuisine.png",
    "avatar-diet.png"
];

document.addEventListener("DOMContentLoaded", () => {
    showQuestion();
    updateAvatar(); // Initial avatar update
});

function showQuestion() {
    const messageContainer = document.getElementById("messages");

    if (currentQuestion < questions.length) {
        const messageElement = document.createElement("div");
        messageElement.className = "message";
        messageElement.innerHTML = `<div class="avatar"></div><div class="text">${questions[currentQuestion]}</div>`;
        messageContainer.appendChild(messageElement);
    } else {
        submitForm();
    }
}

function updateAvatar() {
    const avatarElement = document.querySelector(".avatar");
    if (avatarElement) {
        avatarElement.style.backgroundImage = `url('${avatarImages[currentQuestion]}')`;
    }
}

function handleKeyPress(event) {
    if (event.key === "Enter") {
        nextQuestion();
    }
}

function nextQuestion() {
    const userInput = document.getElementById("user-input").value.trim();
    
    if (userInput === "" && currentQuestion !== 0) return;

    const messageContainer = document.getElementById("messages");

    const userMessageElement = document.createElement("div");
    userMessageElement.className = "message user";
    userMessageElement.innerHTML = `<div class="text">${userInput}</div>`;
    messageContainer.appendChild(userMessageElement);

    answers.push(userInput);
    document.getElementById("user-input").value = "";
    currentQuestion++;

    updateAvatar(); // Update avatar for the next question
    showQuestion();
}

function submitForm() {
    document.getElementById('hiddenLocation').value = answers[0];
    document.getElementById('hiddenCuisine').value = answers[1];
    document.getElementById('hiddenDiet').value = answers[2];
    document.getElementById('recommendationsForm').submit();
}
