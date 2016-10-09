# Doctor PHP ORM
## Synopsis

**Doctor ORM** is a simple ORM developed in PHP. The main objective is to provide automatisation such as constructor, setters and getters to a database, with persistent data.

## Configuration

### Class configurations
I was inspired by Doctrine2 mapping. Everything is configurated through PHP comments. Here a example to define in which table Doctor should look for data :
```php
/**
 * @Table(games)
 */
class Game extends Doctor {

}
```
*Please note that the ```Game``` class extends the ```Doctor``` class !*

To configure attributes and relation-ships, use 
```php
  /**
   * @PrimaryKey
   */
  protected $gid;
```
- ```@PrimaryKey``` indicates that this key is a auto-incremented value. **You can have multiple primary keys.**
```php
  /**
   * @Attribute
   */
  protected $name;
```
- ```@Attribute``` is just an attribute. It **must have** the same name as in the database
```php
  /**
   * @BelongsTo(Editor)
   */
  protected $editor;
```
- ```@BelongsTo(Something)``` indicates that this value is a *Something* object and that it needs to create this object with this id.
```php
/**
   * @HasMany(Game)
   */
  protected $games;
```
- ```@HasMany(Something)``` is the inverse of *BelongsTo*. This attribute will be an array of *Something* objects. To do this, the *Something* class **MUST HAVE** a ```@BelongsTo``` attribute.

*Please note the ```@``` before each option !*

Here a full example :
```php
/**
 * @Table(editors)
 */
class Editor extends Doctor {

  /**
   * @PrimaryKey
   */
  protected $eid;

  /**
   * @Attribute
   */
  protected $name;

  /**
   * @HasMany(Game)
   */
  protected $games;
}
```
```php
/**
 * @Table(editors)
 */
class Editor extends Doctor {

  /**
   * @PrimaryKey
   */
  protected $eid;

  /**
   * @Attribute
   */
  protected $name;

  /**
   * @HasMany(Game)
   */
  protected $games;
}
```

Every class that extends Doctor have a static methode ```debug``` that can help you.
### Database configurations
The ```Doctor``` class extends from ```DoctorConfig```, that a static attributes for database configuration.

Just put this on every page before creating new object.
```php
DoctorConfig::$dbms = 'mysql';
DoctorConfig::$host = 'localhost';
DoctorConfig::$dbname = 'doctor';
DoctorConfig::$port = '3306';
DoctorConfig::$user = 'root';
DoctorConfig::$pass = 'root';
```
## Usage

Once you have configurated every class and ```DoctorConfig```, simply require ```Doctor.php``` at the beginning of the page.
```php
require_once './Doctor/Doctor.php';
```

## Example
### Game.php
```php
<?php

/**
 * @Table(games)
 */
class Game extends Doctor {

  /**
   * @PrimaryKey
   */
  protected $gid;

  /**
   * @Attribute
   */
  protected $name;

  /**
   * @BelongsTo(Editor)
   */
  protected $editor;
}
```
### Editor.php
```php
<?php

/**
 * @Table(editors)
 */
class Editor extends Doctor {

  /**
   * @PrimaryKey
   */
  protected $eid;

  /**
   * @Attribute
   */
  protected $name;

  /**
   * @HasMany(Game)
   */
  protected $games;
}
```
### index.php
```php
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

//Equivalent to print_r($game) but with more presentation
Game::debug($game);
```
## Results
```php
Game Object
(
    [gid:protected] => 1
    [name:protected] => Starcraft 2
    [editor:protected] => Editor Object
        (
            [eid:protected] => 1
            [name:protected] => Blizzard
            [games:protected] => Array
                (
                    [0] => Game Object
                        (
                            [gid:protected] => 1
                            [name:protected] => Starcraft 2
                            [editor:protected] => 1
                        )

                    [1] => Game Object
                        (
                            [gid:protected] => 2
                            [name:protected] => Warcraft 3
                            [editor:protected] => 1
                        )

                )

        )

)
```

**That's it !**

## Contributors

I'm the only on working on this project. 

You can contact me on [my website](www.vincentriva.fr), and follow me on Twitter [@MCpTreast](https://twitter.com/MCpTreast).

## License

This project is under **Creative Commons Licence Attribution (BY)**, that's mean you can :
- Use it for commercial purposes
- Modify it
- Distribute it
- Make derivate works and remixes

if only you write my name (Treast) somewhere visible by the public.
### Example in HTML
```html
<!-- This project run with Doctor ORM by Treast (https://github.com/Treast/Doctor-ORM) -->
```