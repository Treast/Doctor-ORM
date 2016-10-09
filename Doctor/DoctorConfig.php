<?php

class DoctorConfig {

  protected static $instance;

  public static $dbms;
  public static $host;
  public static $dbname;
  public static $port;
  public static $user;
  public static $pass;

  public static function getInstance() {
    if(self::$instance === null) {
      if (isset(self::$host) && isset(self::$dbms) && isset(self::$dbname) && isset(self::$port) && isset(self::$user) && isset(self::$pass)) {
        self::$instance = new PDO(self::$dbms.':host='.self::$host.';dbname='.self::$dbname.';port='.self::$port, self::$user, self::$pass);
      }
      else
        throw new Exception("DoctorConfigException - You need to specify configuration variables. See the documentation for more informations.", 42);   
    }
    return self::$instance;
  }

}