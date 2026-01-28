#!/usr/bin/python3
# -*- coding: utf-8 -*-

import sys
import traceback
import openai
import os

print("Content-Type: text/html; charset=utf-8\n")

try:
    openai.api_key = "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"

    # Correcte bestandslocatie
    file_path = "/home/windels/public_html/py/windels_data.json"

    # Controleer of het bestand bestaat
    if not os.path.exists(file_path):
        raise FileNotFoundError(f"❌ Bestand niet gevonden: {file_path}")

    # Upload het bestand naar OpenAI
    with open(file_path, "rb") as f:
        response = openai.files.create(file=f, purpose="assistants")

    print(f"<h1>✅ Bestand geüpload! File ID: {response.id}</h1>")

except Exception as e:
    print("<h1>❌ Er is een fout opgetreden!</h1>")
    print("<pre>")
    traceback.print_exc(file=sys.stdout)
    print("</pre>")
