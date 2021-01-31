<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder;

use InvalidArgumentException;
use RuntimeException;

class Table extends Base
{

  /**
   * @var string table name
   */
  protected string $name;

  /**
   * @var bool timestamps
   */
  protected bool $timestamps = true;

  /**
   * @var bool soft deletes
   */
  protected bool $softDelete = false;

  /**
   * @var Columns list of columns
   */
  protected Columns $columns;

  /**
   * @var ForeignKeys list of foreign keys
   */
  protected ForeignKeys $foreignKeys;

  /**
   * Table constructor
   *
   * @param string $name
   * 
   * @throws InvalidArgumentException
   */
  public function __construct(string $name, $options)
  {

    $this->setName($name);

    if (array_key_exists('timestamps', $options))
      $this->setTimestamps($options['timestamps']);

    if (array_key_exists('softDelete', $options))
      $this->setSoftDelete($options['softDelete']);

    $this->columns = new Columns;
    $this->foreignKeys = new ForeignKeys;
  }

  /**
   * Name setter
   * 
   * @param string $name
   * @return Table
   * @throws InvalidArgumentException
   */
  public function setName(string $name): Table
  {

    if (!static::validateName($name))
      throw new InvalidArgumentException('Column name is invalid');

    $this->name = $name;

    return $this;
  }

  /**
   * Timestamps setter
   * 
   * @param bool $timestamps
   * @return Table
   */
  public function setTimestamps(bool $timestamps): Table
  {

    $this->timestamps = $timestamps;

    return $this;
  }

  /**
   * Soft Delete setter
   * 
   * @param bool $softDelete
   * @return Table
   */
  public function setSoftDelete(bool $softDelete): Table
  {

    $this->softDelete = $softDelete;

    return $this;
  }

  /**
   * Add a column to table
   * 
   * @param Column $column 
   * @return Table
   */
  public function addColumn(Column $column): Table
  {

    $this->columns[] = $column;

    return $this;
  }

  /**
   * Add a foreign key to table
   * 
   * @param ForeignKey $column 
   * @return Table
   */
  public function addForeignKey(ForeignKey $foreignKey): Table
  {
    $column = $foreignKey->column;
    if (!$this->columnExists($column))
      throw new RuntimeException("Cannot add a foreign key on a column that does not exist: {$column}");

    $this->foreignKeys[] = $foreignKey;

    return $this;
  }

  /**
   * Check if column exists
   * 
   * @param string $name
   * @return bool
   */
  public function columnExists(string $name): bool
  {
    if (empty($this->columns))
      return false;

    foreach ($this->columns as $column) {
      if ($column->name===$name)
        return true;
    }

    return false;
  }

  /**
   * Validates table name will work
   * 
   * @param string $name
   * @return bool
   */
  public static function validateName(string $name): bool
  {
    return preg_match('/^[\p{L}_][\p{L}\p{N}@$#_]{0,127}$/i', $name);
  }
}
