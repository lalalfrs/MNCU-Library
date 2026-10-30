@echo off
net session >nul 2>&1
if %errorLevel% neq 0 (
    powershell -Command "Start-Process cmd -ArgumentList '/c `"%~f0`"' -Verb RunAs"
    exit /b
)

echo Menghentikan service MySQL93...
net stop MySQL93
sc config MySQL93 start= demand
echo.
echo Service MySQL93 berhasil dihentikan!
echo Sekarang MySQL Laragon bisa berjalan normal di port 3306.
timeout /t 3
