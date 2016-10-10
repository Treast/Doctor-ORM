<?php

require_once 'DoctorConfig.php';

class Doctor {

  public function __construct($searchId = null, $recursive = TRUE){
    if (is_null($searchId)) {

    } else {
      if(!is_array($searchId))
        $searchId = array($searchId);
      $query = "SELECT ". join(', ', self::getSelectableProperties()) ." FROM ". self::getTableName() ." WHERE (". join(', ', self::getPropertiesName('PrimaryKey')) .") = (". join(', ', $searchId) .")";
      try {
        self::buildObject(self::query($query), $recursive);
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
  }
  public function __get($field) {
  try {
    return $this->$field;
  } catch (Exception $e) {
    return null;
  }
}

public function delete(){
  $table = self::getTableName();
  $id = self::getPropertiesName('PrimaryKey')[0];
  $query = "DELETE FROM ". $table ." WHERE (". join(', ', self::getPropertiesName('PrimaryKey')) .") = (". $this->$id .")";
  //db()->query($query);
  self::query($query);
}

public function __set($field, $value) {
  if ($value != null) {
    if(property_exists(get_class($this), $field)){
      $table = self::getTableName();
      if(in_array($field, self::getSelectableProperties())){
        $id = self::getPropertiesName('PrimaryKey')[0];
        $query = "UPDATE ". $table ." SET ". $field ." = '". $value ."' WHERE (". join(', ', self::getPropertiesName('PrimaryKey')) .") = (". $this->$id .")";
        self::query($query);
        $this->$field = $value;
      }
    }else{
      throw new Exception("Unknown variable: ".$field);
    }
  }
}

  private static function query($query){
    try {
      $statement = DoctorConfig::getInstance()->query($query)->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
      if ($e->getCode() == 42)
        die($e->getMessage());
      else
        die("DoctorException - Invalid query : ". $query);
    }
    return $statement;
  }

  private function buildObject($statement, $recursive = TRUE){
    if(count($statement) == 1){
      $object = $statement[0];
      foreach ($object as $key => $value) {
        if (array_key_exists('Attribute', self::getPropertyType($key)) || array_key_exists('PrimaryKey', self::getPropertyType($key))) {
            $this->$key = $value;
        } elseif (array_key_exists('BelongsTo', self::getPropertyType($key))) {
          if ($recursive) {
            $class = self::getPropertyType($key)['BelongsTo'];
            $this->$key = new $class($value);
          } else {
            $this->$key = $value;
          }
        }
      }
      foreach (self::getProperties('HasMany') as $key => $value) {
        $class = self::getPropertyType($key)['HasMany'];
        $field = $class::getHasManyName(get_called_class());
          if ($recursive) {
            $fieldId = self::getPropertiesName('PrimaryKey')[0];
            $id = $this->$fieldId;
            $this->$key = $class::findAll(array($field => $id), FALSE);
          }
          else
            $this->$key = null;
      }
    } else {
      throw new Exception("DoctorException - Too much results or zero results.", 42);  
    }
  }

  private static function getProperties($filters = null){
    $properties = array();
    $reflection = new ReflectionClass(get_called_class());
    foreach($reflection->getProperties() as $property){
      $parse = self::parse($property->getDocComment());
      if((is_null($filters)) ? TRUE : array_key_exists($filters, $parse))
        $properties[$property->getName()] = $parse;
    }
    return $properties;
  }

  private static function getHasManyName($class){
    foreach (self::getProperties('BelongsTo') as $key => $value) {
      if ($value['BelongsTo'] == $class)
        return $key;
    }
    return null;
  }

  private static function getSelectableProperties(){
    return array_diff(self::getPropertiesName(), self::getPropertiesName('HasMany'));
  }

  private static function getPropertiesName($filters = null){
    return array_keys(self::getProperties($filters));
  }

  private static function getPropertyType($property){
    return self::getProperties()[$property];
  }

  private static function getTableName(){
    $reflection = new ReflectionClass(get_called_class());
    return self::parse($reflection->getDocComment())['Table'];
  }

  private static function parse($comments){
    $parser = array();
    $lines = explode("\n", $comments);

    foreach($lines as $line){
      preg_match('/\* \@([a-zA-Z0-9]+)\(([a-zA-Z0-9_\-]+)\)/', $line, $matches);
      if(!empty($matches[0])){
        $parser[$matches[1]] = $matches[2];
      }else{
        preg_match('/\* \@([a-zA-Z0-9]+)/', $line, $matches);
        if(!empty($matches[0]))
          $parser[$matches[1]] = 1;
      }
    }
    return $parser;
  }

  public static function findAll($conditions = array(), $recursive = TRUE) {
    $class = get_called_class();
    $query = "SELECT ". join(', ', self::getSelectableProperties()) ." FROM ". self::getTableName();
    
    if (count($conditions) > 0) {
      $query .= " WHERE ";
      foreach ($conditions as $key => $value) {
        $query .= $key ." = ". $value ." AND ";
      }
      $query = substr($query, 0, -4);
    }
    
    $objects = $class::query($query);
    $instances = array();
    foreach($objects as $object) {
      $ids = array();
      foreach ($object as $key => $value) {
        if (array_key_exists('PrimaryKey', self::getPropertyType($key)))
          $ids[] = $value;
      }
      $instances[] = new $class($ids, $recursive);
    }
    return $instances;
  }

  public static function debug($v){
    echo '<pre>'.print_r($v, TRUE).'</pre>';
  }

}