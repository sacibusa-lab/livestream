import urllib.request
import re
import json

url = "https://www.fifa.com/en/tournaments/mens/worldcup/canadamexicousa2026/scores-fixtures?country=&wtw-filter=ALL"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
try:
    html = urllib.request.urlopen(req).read().decode('utf-8')
    match = re.search(r'<script id="__NEXT_DATA__" type="application/json">(.*?)</script>', html)
    if match:
        data = json.loads(match.group(1))
        with open("fifa_data.json", "w", encoding="utf-8") as f:
            json.dump(data, f, indent=2)
        print("Successfully dumped FIFA data.")
    else:
        print("Could not find __NEXT_DATA__ in HTML.")
except Exception as e:
    print("Error:", e)
