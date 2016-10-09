<?php

require 'Doctor/Doctor.php';

function autoloader($class) {
  $nameClass = ucfirst($class) .'.php';
  if(file_exists($nameClass))
    require $nameClass;
}

DoctorConfig::$dbms = 'mysql';
DoctorConfig::$host = 'localhost';
DoctorConfig::$dbname = 'scotchbox';
DoctorConfig::$port = '3306';
DoctorConfig::$user = 'root';
DoctorConfig::$pass = 'root';

spl_autoload_register('autoloader');

Game::debug(new Game(3));