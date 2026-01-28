<?php
include $_SERVER['DOCUMENT_ROOT'] . '/header.php';
?>
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="card shadow-lg p-4 main-content">
                <!-- Knoppenweergave op basis van rol -->
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-home"></i>
                                    Home </h5>
                                <a href="/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Home </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fa fa-envelope"></i>
                                    Berichten </h5>
                                <a href="/andy/send_message.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Berichten </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-calendar"></i>
                                    Kalender </h5>
                                <a href="/andy/kantoor/calendar.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Kalender </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-clock"></i>
                                    klok in/out system rapport </h5>
                                <a href="/andy/user_reports.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar klok in/out system rapport </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-calendar"></i>
                                    uurrooster </h5>
                                <a href="/andy/admin_rooster.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar uurrooster </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    loonfiche maken </h5>
                                <a href="/andy/manage_payslips.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar loonfiche maken </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-rss"></i>
                                    Rss </h5>
                                <a href="/andy/rss/manage.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Rss </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-rss"></i>
                                    Rss balk </h5>
                                <a href="/andy/rss_balk/manage.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Rss balk </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-percentage"></i>
                                    Korting/Actie&#039;s </h5>
                                <a href="/andy/kantoor/kortingen/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Korting/Actie&#039;s </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-percentage"></i>
                                    Kortingen lijst winkel </h5>
                                <a href="/andy/kantoor/kortingen/korting.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Kortingen lijst winkel </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-image"></i>
                                    weekdeal scherm </h5>
                                <a href="/andy/kantoor/kortingen/viewimage-weekdeal.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar weekdeal scherm </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fa fa-tasks"></i>
                                    Taken </h5>
                                <a href="/andy/kantoor/tasks_overview.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Taken </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-cash-register"></i>
                                    Kassa Beheer </h5>
                                <a href="/andy/kantoor/cash_count.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Kassa Beheer </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-book"></i>
                                    Boekhouding </h5>
                                <a href="/andy/kantoor/boekhouding/index.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Boekhouding </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fas fa-bullhorn"></i>
                                    Gdpr wijzigen </h5>
                                <a href="/andy/gdpr_admin.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Gdpr wijzigen </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    <i class="fa fa-users"></i>
                                    Klantenbestand </h5>
                                <a href="/andy/kantoor/klantenbestand.php" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i> Ga naar Klantenbestand </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <style>
            .card {
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .card-header {
                font-size: 1.2rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
            }

            .chat-window {
                height: 400px;
                overflow-y: auto;
                border-bottom: 1px solid #e0e0e0;
            }

            .message-input {
                display: flex;
                align-items: center;
                margin-top: 1rem;
            }

            .chat-input {
                flex-grow: 1;
                padding: 10px;
                font-size: 1rem;
                border-radius: 5px;
                border: solid 1px #ccc;
                outline: none;
            }

            .chat-send-btn {
                background-color: #3b6e00;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-left: 10px;
                display: flex;
                align-items: center;
            }

            .chat-send-btn:hover {
                background-color: #1c8a03;
            }

            /* Mobielvriendelijke Aanpassingen */
            @media (max-width: 768px) {
                .card {
                    width: 100%;
                    margin: 0 auto;
                }

                .chat-window {
                    height: 300px;
                }

                .message-input {
                    flex-direction: column;
                    align-items: stretch;
                }

                .chat-input {
                    width: 100%;
                    margin-bottom: 10px;
                }

                .chat-send-btn {
                    width: 100%;
                    padding: 12px;
                }
            }

            @media (max-width: 480px) {
                .card-header {
                    font-size: 1rem;
                    padding: 0.8rem;
                }

                .chat-window {
                    height: 250px;
                    padding: 0.5rem;
                }

                .message-input {
                    margin-top: 0.5rem;
                }

                .chat-input {
                    padding: 8px;
                    font-size: 0.9rem;
                }

                .chat-send-btn {
                    font-size: 0.9rem;
                    padding: 10px;
                }
            }

            .chat-container {
                width: 100%;
                max-width: 600px;
                height: 500px;
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .chat-header {
                background-color: green;
                color: white;
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 400px; /* Stel de vaste hoogte in */
                overflow-y: auto;
            }

            .chat-avatar {
                border-radius: 50%;
                margin-right: 10px;
            }

            .chat-status {
                font-size: 0.9em;
                color: white;
                font-weight: bold;
            }

            .chat-window {
                height: 400px; /* Stel de vaste hoogte in */
                overflow-y: auto; /* Voeg een verticale scrollbalk toe indien nodig */
                border: 1px solid #ddd;
                padding: 10px;
                background-color: #ffe8d0;
            }

            /* Chatbubbels */
            .chat-bubble {
                max-width: 75%;
                margin-bottom: 10px;
                padding: 10px 15px;
                border-radius: 20px;
                font-size: 14px;
                line-height: 1.4;
                position: relative;
                word-wrap: break-word;
            }

            /* Berichten van andere gebruikers aan de linkerkant */
            .chat-bubble.you {
                background-color: #e9f5ff;
                align-self: flex-start; /* Links uitlijnen */
                border-bottom-left-radius: 0; /* Verwijder onderste linkerhoek voor stijl */
                margin-right: auto; /* Zorg ervoor dat het bericht links blijft */
            }

            /* Berichten van de gebruiker zelf aan de rechterkant */
            .chat-bubble.me {
                background-color: green;
                color: white;
                align-self: flex-end; /* Rechts uitlijnen */
                border-bottom-right-radius: 0; /* Verwijder onderste rechterhoek voor stijl */
                margin-left: auto; /* Zorg ervoor dat het bericht rechts blijft */
                text-align: right; /* Tekst rechts uitlijnen */
            }


            .chat-footer {
                display: flex;
                padding: 10px;
                background-color: white;
                border-top: 1px solid #ddd;
            }

            .message-input {
                display: flex;
                align-items: center;
            }

            .chat-input {
                flex: 1; /* Neemt alle beschikbare ruimte in */
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 16px;
            }

            .chat-send-btn {
                background-color: #28a745; /* Groene knop */
                color: white;
                border: none;
                padding: 10px 20px;
                margin-left: 10px; /* Ruimte tussen invoerveld en knop */
                border-radius: 4px;
                font-size: 16px;
                cursor: pointer;
            }

            .chat-send-btn:hover {
                background-color: #218838; /* Donkergroen bij hover */
            }

            .typing-status {
                font-size: 0.9em;
                color: #999;
                padding: 5px;
                margin-top: -10px;
            }

            /* Tijdstempel styling */
            .chat-bubble.me .chat-time {
                display: block;
                font-size: 0.75em;
                color: #fff;
                margin-top: 5px;
                text-align: right; /* Tijd rechts voor je eigen berichten */
            }

            /* Tijdstempel styling */
            .chat-bubble.you .chat-time {
                display: block;
                font-size: 0.75em;
                color: #999;
                margin-top: 5px;
                text-align: right; /* Tijd rechts voor je eigen berichten */
            }

            /* Overschrijven van de Bootstrap active class voor list-group-items */
            .list-group-item.active {
                background-color: #28a745 !important; /* Groene achtergrondkleur met !important */
                color: white !important; /* Witte tekst voor contrast met !important */
                border-color: #28a745 !important; /* Optioneel: verander ook de randkleur */
            }

            /* Normale list-group-item */
            .list-group-item {
                background-color: white; /* Witte achtergrond voor niet-actieve items */
                color: black; /* Zwarte tekstkleur */
            }

            /* Hover-effect voor niet-geactiveerde gebruikers */
            .list-group-item:hover {
                background-color: #f1f1f1; /* Lichtgrijze achtergrondkleur bij hover */
            }

            @media (max-width: 768px) {
                .chat-container {
                    max-width: 100%;
                    height: 400px;
                }
            }

            .chat-popup {
                display: none;
                position: fixed;
                bottom: 20px;
                right: 20px;
                width: 90%;
                max-width: 900px;
                max-height: 100vh;
                background: white;
                border-radius: 10px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                overflow: hidden;
                z-index: 1000;

            }

            .chat-header {
                padding: 8px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 50px;
            }

            .close-btn {
                background: none;
                border: none;
                color: white;
                font-size: 20px;
                cursor: pointer;
            }

            .chat-body {
                overflow-y: auto;
                max-height: 100vh;
                padding: 25px;
            }

            #chat-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                background-color: green;
                color: white;
                border: none;
                border-radius: 90px;
                width: 60px;
                height: 60px;
                font-size: 24px;
                cursor: pointer;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            }
        </style>
        <div id="chat-popup" class="chat-popup shadow-lg">
            <div class="chat-header bg-success text-white  align-items-center" style="padding: 8px; height: 50px;">
                <h4><i class="fa fa-comments"></i> Chat met collega's</h4>
                <button id="close-chat" class="close-btn"
                        style="font-size: 1.2rem; background: none; border: none; color: white;">&times;
                </button>
            </div>
            <div class="chat-body">
                <div class="row">
                    <!-- Gebruikerslijst links -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <i class="fa fa-users"></i> Gebruikerslijst
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <form method="GET" action="index.php#chat"
                                          style="display: flex; align-items: center; width: 100%;">
                                        <input type="hidden" name="receiver_id" value="1">
                                        <button type="submit" class="btn btn-link"
                                                style="text-decoration: none; color: inherit; width: 100%; text-align: left;">
                                            <span class="online-status" style="color: gray; font-size: 1.2em;">●</span>
                                            <span class="user-name">Andy Windels</span>
                                            <span id="badge-1"
                                                  class="badge badge-danger badge-pill user-badge d-none">0</span>
                                        </button>
                                    </form>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <form method="GET" action="index.php#chat"
                                          style="display: flex; align-items: center; width: 100%;">
                                        <input type="hidden" name="receiver_id" value="2">
                                        <button type="submit" class="btn btn-link"
                                                style="text-decoration: none; color: inherit; width: 100%; text-align: left;">
                                            <span class="online-status" style="color: gray; font-size: 1.2em;">●</span>
                                            <span class="user-name">Andy Windels</span>
                                            <span id="badge-2"
                                                  class="badge badge-danger badge-pill user-badge d-none">0</span>
                                        </button>
                                    </form>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <form method="GET" action="index.php#chat"
                                          style="display: flex; align-items: center; width: 100%;">
                                        <input type="hidden" name="receiver_id" value="3">
                                        <button type="submit" class="btn btn-link"
                                                style="text-decoration: none; color: inherit; width: 100%; text-align: left;">
                                            <span class="online-status" style="color: gray; font-size: 1.2em;">●</span>
                                            <span class="user-name">Henri Cools</span>
                                            <span id="badge-3"
                                                  class="badge badge-danger badge-pill user-badge d-none">0</span>
                                        </button>
                                    </form>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <form method="GET" action="index.php#chat"
                                          style="display: flex; align-items: center; width: 100%;">
                                        <input type="hidden" name="receiver_id" value="5">
                                        <button type="submit" class="btn btn-link"
                                                style="text-decoration: none; color: inherit; width: 100%; text-align: left;">
                                            <span class="online-status" style="color: gray; font-size: 1.2em;">●</span>
                                            <span class="user-name">Franky Windels</span>
                                            <span id="badge-5"
                                                  class="badge badge-danger badge-pill user-badge d-none">0</span>
                                        </button>
                                    </form>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <form method="GET" action="index.php#chat"
                                          style="display: flex; align-items: center; width: 100%;">
                                        <input type="hidden" name="receiver_id" value="6">
                                        <button type="submit" class="btn btn-link"
                                                style="text-decoration: none; color: inherit; width: 100%; text-align: left;">
                                            <span class="online-status" style="color: gray; font-size: 1.2em;">●</span>
                                            <span class="user-name">Diane De smedt</span>
                                            <span id="badge-6"
                                                  class="badge badge-danger badge-pill user-badge d-none">0</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Chatvenster -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <i class="fa fa-comments"></i> Chat met <span id="chat-with">Niemand</span>
                            </div>
                            <div id="chat-window" class="chat-window p-3">
                                <p class='text-muted'>Selecteer een gebruiker om te chatten.</p></div>
                            <div id="typing-status" class="text-muted"></div>
                            <div class="card-footer">
                                <form method="POST" action="">
                                    <input type="hidden" name="receiver_id" value="">
                                    <div class="message-input">
                                        <input type="text" name="message" class="chat-input"
                                               placeholder="Typ een bericht..." required>
                                        <button type="submit" name="send" class="chat-send-btn">
                                            <i class="fas fa-paper-plane"></i> Verzenden
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- JavaScript om Pop-up te openen/sluiten -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("chat-button").addEventListener("click", function () {
                    document.getElementById("chat-popup").style.display = "block";
                });

                document.getElementById("chat-href").addEventListener("click", function () {
                    document.getElementById("chat-popup").style.display = "block";
                });

                document.getElementById("close-chat").addEventListener("click", function () {
                    document.getElementById("chat-popup").style.display = "none";
                });
            });
        </script>

        <!-- Knop om chat te openen -->
        <button id="chat-button">💬</button>

    </div>
    <div class="footer bg-dark text-white text-center py-3">
        <p class="mr-auto">&copy; 2025 Windels Green & Deco Resin. Alle rechten voorbehouden.</p>
        <p class="ml-auto">Versie: 0.0.6.3</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function checkOpeningTime() {
                fetch('winkel_popup_status.php?status=true')
                    .then(response => response.json())
                    .then(data => {
                        if (data.gesloten) {
                            console.log('Winkel is gesloten vanwege: ' + data.reden);
                            return;
                        }
                        let currentTime = new Date();
                        let currentHour = currentTime.getHours();
                        let currentMinute = currentTime.getMinutes();
                        let openingTime = data.openTijd;
                        let tenMinutesBeforeOpen = data.tienMinVoorOpen;
                        let closingTime = data.sluitTijd;
                        let tenMinutesBeforeClose = data.tienMinVoorSluit;

                        if (openingTime && tenMinutesBeforeOpen) {
                            let openHour = parseInt(openingTime.split(':')[0]);
                            let openMinute = parseInt(openingTime.split(':')[1]);
                            let tenMinBeforeOpenHour = parseInt(tenMinutesBeforeOpen.split(':')[0]);
                            let tenMinBeforeOpenMinute = parseInt(tenMinutesBeforeOpen.split(':')[1]);

                            if (currentHour === tenMinBeforeOpenHour && currentMinute === tenMinBeforeOpenMinute) {
                                alert('⏳ De winkel opent binnenkort om ' + openingTime + '!');
                            }
                        }

                        if (closingTime && tenMinutesBeforeClose) {
                            let closeHour = parseInt(closingTime.split(':')[0]);
                            let closeMinute = parseInt(closingTime.split(':')[1]);
                            let tenMinBeforeCloseHour = parseInt(tenMinutesBeforeClose.split(':')[0]);
                            let tenMinBeforeCloseMinute = parseInt(tenMinutesBeforeClose.split(':')[1]);

                            if (currentHour === tenMinBeforeCloseHour && currentMinute === tenMinBeforeCloseMinute) {
                                alert('⚠️ De winkel sluit binnenkort om ' + closingTime + '!');
                            }
                        }
                    })
                    .catch(error => console.error('Fout bij ophalen van winkelstatus:', error));
            }

            setInterval(checkOpeningTime, 60000); // Controleer elke minuut
        });
    </script>





<?php

include $_SERVER['DOCUMENT_ROOT'] . '/footer.php';
