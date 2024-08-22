@echo off
mkdir cat-cpns-simulation
cd cat-cpns-simulation

mkdir assets\css assets\js assets\images
mkdir config
mkdir includes
mkdir admin
mkdir user
mkdir classes

echo. > assets\css\custom.css
echo. > assets\js\custom.js
echo. > config\database.php
echo. > includes\header.php
echo. > includes\footer.php
echo. > admin\index.php
echo. > admin\manage_questions.php
echo. > admin\manage_users.php
echo. > user\index.php
echo. > user\profile.php
echo. > user\take_test.php
echo. > classes\User.php
echo. > classes\Question.php
echo. > classes\Test.php
echo. > index.php
echo. > login.php
echo. > register.php
echo. > README.md

echo Struktur folder dan file telah dibuat.