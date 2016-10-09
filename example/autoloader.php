<?php

function autoloader($class) {
  $nameClass = ucfirst($class) .'.php';
  if(file_exists($nameClass))
    require $nameClass;
}

spl_autoload_register('autoloader');