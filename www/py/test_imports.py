#!/usr/bin/python3
# -*- coding: utf-8 -*-

import sys
import traceback
import requests
from bs4 import BeautifulSoup
import json

print("Content-Type: text/html; charset=utf-8\n")

def scrape_website(url, output_file):
    """Scrapet de opgegeven URL en slaat de tekst op in een JSON-bestand."""
    try:
        response = requests.get(url)
        response.raise_for_status()  # Geeft een fout als de pagina niet beschikbaar is

        # HTML parseren en alleen de tekstinhoud ophalen
        soup = BeautifulSoup(response.text, "html.parser")
        text_content = soup.get_text(separator=" ").strip()

        # Data opslaan in een JSON-bestand
        data = {
            "company_name": "Windels Green & Deco Resin",
            "website_content": text_content
        }

        with open(output_file, "w", encoding="utf-8") as f:
            json.dump(data, f, indent=4, ensure_ascii=False)

        print("<html><body>")
        print(f"<h1>✅ Website-content opgeslagen in {output_file}!</h1>")
        print("</body></html>")

    except Exception as e:
        print("<h1>❌ Er is een fout opgetreden!</h1>")
        print("<pre>")
        traceback.print_exc(file=sys.stdout)
        print("</pre>")

# Roep de functie aan om de website te scrapen
scrape_website("https://windelsgreen-decoresin.com/", "windels_data.json")
