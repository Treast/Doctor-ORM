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