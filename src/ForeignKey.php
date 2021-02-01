<?php

namespace BlackBrickSoftware\MigrationBuilder;

use InvalidArgumentException;
use UnexpectedValueException;

class ForeignKey
{

  /**
   * @var string column name
   */
  protected string $column;

  /**
   * @var string references column name
   */
  protected string $references;

  /**
   * @var string table name
   */
  protected string $on;

  /**
   * @var string|null on delete
   */
  protected ?string $onDelete = null;

  /**
   * @const array
   */
  const SUPPORTED_ON_DELETES = [
    'restrict',
    'cascade',
    'set null',
  ];

  /**
   * Table constructor
   *
   * @param string $references
   * @param string $on
   * @throws InvalidArgumentException
   */
  public function __construct(string $column, string $references, string $on, array $options = [])
  {
    $this->setColumn($column);
    $this->setReferences($references);
    $this->setOn($on);

    if (array_key_exists('onDelete', $options))
      $this->setOnDelete($options['onDelete']);
  }

  /**
   * Column setter
   * 
   * @param string $Column
   * @return ForeignKey
   * @throws InvalidArgumentException
   */
  public function setColumn(string $column): ForeignKey
  {
    if (!Column::validateName($column))
      throw new InvalidArgumentException('Column name is invalid');

    $this->column = $column;

    return $this;
  }

  /**
   * References setter
   * 
   * @param string $references
   * @return ForeignKey
   * @throws InvalidArgumentException
   */
  public function setReferences(string $references): ForeignKey
  {
    if (!Column::validateName($references))
      throw new InvalidArgumentException('References column name is invalid');

    $this->references = $references;

    return $this;
  }

  /**
   * On setter
   * 
   * @param string $on
   * @return ForeignKey
   * @throws InvalidArgumentException
   */
  public function setOn(string $on): ForeignKey
  {
    if (!Table::validateName($on))
      throw new InvalidArgumentException('Table name is invalid');

    $this->on = $on;

    return $this;
  }

  /**
   * onDelete setter
   * 
   * @param string|null $onDelete
   * @return ForeignKey
   * @throws InvalidArgumentException
   */
  public function setOnDelete(?string $onDelete): ForeignKey
  {

    if ($onDelete!==null) {

      if (!in_array($this->type, static::SUPPORTED_ON_DELETES))
        throw new UnexpectedValueException("Unsupport On Delete value");
    }

    $this->onDelete = $onDelete;

    return $this;
  }
}
