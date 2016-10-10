<?php

// Just my autoloader
require 'autoloader.php';

//URI to Doctor Class
require '../src/Doctor/Doctor.php';

//Doctor database configuration
DoctorConfig::$dbms = 'mysql';
DoctorConfig::$host = 'localhost';
DoctorConfig::$dbname = 'doctor';
DoctorConfig::$port = '3306';
DoctorConfig::$user = 'root';
DoctorConfig::$pass = 'root';

//Get the game with the id = 1
$game = new Game(1);
$game->name = "Starcraft 3";
$game->editor = 2;
$game->delete();
//Equivalent to print_r($game) but with more presentation
Game::debug($game);