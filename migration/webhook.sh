#!/usr/bin/env bash

# here are some commands that you will need to setup your Telegram bot

# settings of your webhook

TOKEN="yourtelegrambottoken"
WEBHOOK="https://telegram.domain.com/bot/index.php"
DOMAIN="telegram.domain.com"

# settings for your self-signed certificate

CRT="/path/to/your.crt"
KEY="/path/to/your.key"

# generate self-signed SSL certificate

openssl req -newkey rsa:2048 -sha256 -nodes -keyout "$KEY" -x509 -days 365  -out "$CRT" -subj "/C=IT/ST=state/L=location/O=description/CN=$DOMAIN"

# don't forget to open SSL port

sudo ufw allow 443/tcp

# delete webhook

curl "https://api.telegram.org:443/bot$TOKEN/setwebhook?url="

# set webhook

curl \
  -F "url=$WEBHOOK" \
  -F "certificate=@$CRT" \
  "https://api.telegram.org/bot$TOKEN/setwebhook"