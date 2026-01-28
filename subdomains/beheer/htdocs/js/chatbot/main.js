$(document).ready(function () {
    const chatBox = $('#messages');
    const userInput = $('#user-input');
    const sendBtn = $('#send-btn');
    const emojiBtn = $('#emoji-btn');
    const chatbotContainer = $("#chatbot-container");
    const collapseButton = $("#collapseButton");
    const chatbotIcon = $("#chatbot-icon");

    function appendMessage(sender, text) {
        if (!text) return; // Voorkom errors als de tekst undefined of leeg is

        let safeText = $('<div>').text(text).html(); // Voorkomt XSS en rare karakters
        let msgDiv = $('<div>').addClass('message ' + sender);

        try {
            msgDiv.html(twemoji.parse(safeText)); // Probeer emoji parsing
        } catch (error) {
            console.error("Emoji parsing error:", error);
            msgDiv.text(safeText); // Fallback naar gewone tekst
        }

        chatBox.append(msgDiv);
        chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    // Minimaliseer chatbot bij klik op sluit-icoon
    collapseButton.on("click", function () {
        chatbotContainer.addClass("minimized");
        chatbotIcon.removeClass("d-none").css("display", "block");
    });

    // Toon chatbot bij klik op icoon
    chatbotIcon.on("click", function () {
        chatbotContainer.removeClass("minimized");
        chatbotIcon.addClass("d-none").css("display", "none");
    });

    function fetchChatHistory() {
        $.getJSON('/API/AI/chat_history.php', function (data) {
            $.each(data, function (index, msg) {
                appendMessage(msg.role, msg.message);
            });
        });
    }

    sendBtn.click(function () {
        let userMessage = userInput.val().trim();
        if (userMessage === '') return;

        appendMessage('user', userMessage);
        userInput.val('');

        $.ajax({
            url: '/API/AI/chatbot.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ message: userMessage }),
            success: function (data) {
                appendMessage('bot', data.reply);
            },
            error: function () {
                appendMessage('bot', 'Er is een fout opgetreden!');
            }
        });
    });

    userInput.keypress(function (event) {
        if (event.which === 13) sendBtn.click();
    });

    emojiBtn.click(function () {
        userInput.val(userInput.val() + '😊').focus();
    });

    fetchChatHistory();

    // Controleer of chatbot-container minimized is en pas display van chatbot-icon aan
    setInterval(() => {
        if (chatbotContainer.hasClass("minimized")) {
            chatbotIcon.css("display", "block");
        } else {
            chatbotIcon.css("display", "none");
        }
    }, 500);
});
