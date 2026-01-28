$(document).ready(function () {
    const chatBox = $('#messages');
    const userInput = $('#user-input');
    const sendBtn = $('#send-btn');
    const emojiBtn = $('#emoji-btn');
    const chatbotContainer = $("#chatbot-container");
    const collapseButton = $("#collapseButton");
    const chatbotIcon = $("#chatbot-icon");

    // ✅ Markdown links omzetten naar HTML links
    function convertMarkdownLinks(text) {
        return text.replace(/\[([^\]]+)]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1</a>');
    }

    // ✅ Bericht toevoegen aan chatbox
    function appendMessage(sender, text) {
        if (!text) return;

        let msgDiv = $('<div>').addClass('message ' + sender);

        // HTML entities decoderen zodat &amp; → & wordt
        const txt = document.createElement('textarea');
        txt.innerHTML = text;
        let decodedText = txt.value;

        // ✅ Markdown links omzetten naar echte <a> tags
        decodedText = convertMarkdownLinks(decodedText);

        try {
            msgDiv.html(twemoji.parse(decodedText)); // Emoji parsing op decoded tekst
        } catch (error) {
            console.error("Emoji parsing error:", error);
            msgDiv.html(decodedText); // Fallback naar gewone tekst met links
        }

        chatBox.append(msgDiv);
        chatBox.scrollTop(chatBox[0].scrollHeight);
    }

    // ✅ Minimaliseer chatbot bij klik op sluit-icoon
    collapseButton.on("click", function () {
        chatbotContainer.addClass("minimized");
        chatbotIcon.removeClass("d-none").css("display", "block");
    });

    // ✅ Toon chatbot bij klik op icoon
    chatbotIcon.on("click", function () {
        chatbotContainer.removeClass("minimized");
        chatbotIcon.addClass("d-none").css("display", "none");
    });

    // ✅ Chatgeschiedenis ophalen
    function fetchChatHistory() {
        $.getJSON('/API/AI/chat_history.php', function (data) {
            $.each(data, function (index, msg) {
                appendMessage(msg.role, msg.message);
            });
        });
    }

    // ✅ Bericht verzenden
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

    // ✅ Enter-toets verzendt bericht
    userInput.keypress(function (event) {
        if (event.which === 13) sendBtn.click();
    });

    // ✅ Emoji toevoegen
    emojiBtn.click(function () {
        userInput.val(userInput.val() + '😊').focus();
    });

    // ✅ Chatgeschiedenis laden bij opstarten
    fetchChatHistory();

    // ✅ Chatbot icoon weergeven als geminimaliseerd
    setInterval(() => {
        if (chatbotContainer.hasClass("minimized")) {
            chatbotIcon.css("display", "block");
        } else {
            chatbotIcon.css("display", "none");
        }
    }, 500);
});
