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