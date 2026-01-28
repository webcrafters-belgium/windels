<html>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{titlemail}</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #F4F8F0;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .header {
                background-color: #F2BB77;
                color: #ffffff;
                text-align: center;
                padding: 30px;
            }
            .header img {
                max-width: 100px;
                margin-bottom: 10px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
            }
            .body {
                padding: 20px;
                color: #333333;
                line-height: 1.6;
            }
            .body h2 {
                margin: 0 0 10px;
                color: #F2BB77;
            }
            .body p {
                margin: 10px 0;
            }
            .footer {
                background-color: #F4F8F0;
                text-align: center;
                padding: 20px;
                font-size: 14px;
                color: #777777;
            }
        </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <img src="https://medewerkers.windelsgreen-decoresin.com/img/nieuwlogonametransparant.png" alt="Logo">
                    <h1>{headermail}</h1>
                </div>
                <div class="body">
                    {bodymail}
                </div>
                <div class="footer">
                    <p><strong>Met vriendelijke groeten,</strong></p>
                    <p><strong>Windels green & deco resin<br>Windels Andy</strong><br>Beukenlaan 8, 3930 Hamont-Achel, Belgi&euml;<br>BE0803859883<br>+32(0)11753319<br><a href="mailto:info@windelsgreen-decoresin.com">info@windelsgreen-decoresin.com</a></p>
                </div>
            </div>
        </body>
        </html>