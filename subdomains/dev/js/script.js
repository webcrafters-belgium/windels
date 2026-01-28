const teksten = [
    "Een serene omgeving voor het bestellen van gepersonaliseerde decoraties met asverwerking.",
    "Een waardig aandenken voor dierbare herinneringen.",
    "Betrouwbaar, stijlvol en persoonlijk voor elke uitvaartdienst.",
    "Met zorg en aandacht gemaakt, speciaal voor u."
];

let index = 0;
const textElement = document.getElementById("rotating-text");

function toonTekst() {
    textElement.style.opacity = 0;
    setTimeout(() => {
        textElement.textContent = teksten[index];
        textElement.style.opacity = 1;
        index = (index + 1) % teksten.length;
    }, 500);
}

// Start direct
toonTekst();
// Wissel elke 4 seconden
setInterval(toonTekst, 4000);

