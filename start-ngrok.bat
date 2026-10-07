@echo off
title Chay Ngrok Webhook SePay - Gia Dung Family
echo ===================================================
echo   DANG KHOI CHAY NGROK CHO SEPAY WEBHOOK
echo   Domain: https://equate-leggings-sessions.ngrok-free.dev
echo ===================================================
cd /d %~dp0
.\ngrok.exe http --url=equate-leggings-sessions.ngrok-free.dev 80
pause
