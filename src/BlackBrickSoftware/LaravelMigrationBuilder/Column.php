<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder;

use InvalidArgumentException;
use RuntimeException;
use UnexpectedValueException;

class Column extends Base
{

  /**
   * @var string table name
   */
  protected string $name;

  /**
   * @var string column type
   */
  protected string $type;

  /**
   * @const array NEEDS CHECKED
   */
  const SUPPORTED_TYPES = [
    // Numeric
    'tinyInteger',
    'smallInteger',
    'integer',
    'mediumInteger',
    'bigInteger',
    'boolean',
    'decimal',
    'float',
    'double',
    // Date and time
    'date',
    'dateTime',
    'timestamp',
    'time',
    // String
    'string',
    'char',
    'tinyText',
    'text',
    'mediumText',
    'longText',
  ];

  /**
   * @var integer|null length
   */
  protected ?int $length = null;

  /**
   * @const array NEEDS CHECKED
   */
  const COLUMN_WITH_LENGTH = [
    'char',
    'decimal',
    'double',
    'float',
    'string',
  ];

  /**
   * @var integer|null length
   */
  protected ?int $fractional = null;

  /**
   * @const array NEEDS CHECKED
   */
  const COLUMN_WITH_FRACTION = [
    'decimal',
    'double',
    'float',
  ];

  /**
   * @var mixed|null default value
   */
  protected $defaultValue = null;

  /**
   * @const array NEEDS CHECKED
   */
  const COLUMN_WITH_DEFAULT_VALUE = [
    // Numeric
    'tinyInteger',
    'smallInteger',
    'integer',
    'mediumInteger',
    'bigInteger',
    'boolean',
    'decimal',
    'float',
    'double',
    // Date and time
    'date',
    'dateTime',
    'timestamp',
    'time',
    // String
    'string',
    'char',
  ];

  /**
   * @var string|null comment
   */
  protected ?string $comment = null;

  /**
   * @var bool autoIncrement
   */
  protected bool $autoIncrement = false;

  /**
   * @const array NEEDS CHECKED
   */
  const COLUMN_WITH_AUTO_INCREMENT = [
    // Integers
    'tinyInteger',
    'smallInteger',
    'integer',
    'mediumInteger',
    'bigInteger',
  ];

  /**
   * @var bool nullable
   */
  protected bool $nullable = false;

  /**
   * @var bool unique
   */
  protected bool $unique = false;

  /**
   * @var bool index
   */
  protected bool $index = false;

  /**
   * @var bool unsigned
   */
  protected bool $unsigned = false;

  /**
   * @const array
   */
  const COLUMN_WITH_UNSIGNED = [
    // Integers
    'tinyInteger',
    'smallInteger',
    'integer',
    'mediumInteger',
    'bigInteger',
  ];

  /**
   * Table constructor
   *
   * @param string $name
   */
  public function __construct(string $name, string $type, $options)
  {

    $this->setName($name);
    $this->setType($type);

    /**
     * Set Properties of column
     * Order is important to not cause exception inadvertantly
     */

    if (array_key_exists('autoIncrement', $options))
      $this->setDefaultValue($options['autoIncrement']);

    if (array_key_exists('length', $options)) {
      $fractional = $options['fractional'] ?? null;
      $this->setLength($options['length'], $fractional);
    }

    if (array_key_exists('defaultValue', $options))
      $this->setDefaultValue($options['defaultValue']);

    if (array_key_exists('comment', $options))
      $this->setComment($options['comment']);

    if (array_key_exists('nullable', $options))
      $this->setNullable($options['nullable']);

    if (array_key_exists('unique', $options))
      $this->setUnique($options['unique']);

    if (array_key_exists('index', $options))
      $this->setIndex($options['index']);

    if (array_key_exists('unsigned', $options))
      $this->setUnsigned($options['unsigned']);
  }

  /**
   * Name setter
   * 
   * @param string $name
   * @return Column
   * @throws InvalidArgumentException
   */
  public function setName(string $name): Column
  {

    if (!static::validateName($name))
      throw new InvalidArgumentException('Column name is invalid');

    $this->name = $name;

    return $this;
  }

  /**
   * Type setter
   * 
   * @param string $type
   * @return Column
   * @throws InvalidArgumentException
   */
  public function setType(string $type): Column
  {

    if (!in_array($type, static::SUPPORTED_TYPES))
      throw new InvalidArgumentException('Type is not supported');

    $this->type = $type;

    return $this;
  }

  /**
   * Length setter
   * 
   * @param int $length
   * @param int $fractional
   * @return Column
   * @throws InvalidArgumentException
   */
  public function setLength(int $length, int $fractional = null): Column
  {

    if (!in_array($this->type, static::COLUMN_WITH_LENGTH))
      throw new InvalidArgumentException("Lengh is not supported on column type {$this->type}");

    $this->length = $length;

    if ($fractional !== null && !in_array($this->type, static::COLUMN_WITH_FRACTION))
      throw new InvalidArgumentException("Fractional is not supported on column type {$this->type}");

    if ($fractional !== null)
      $this->fractional = $fractional;

    return $this;
  }

  /**
   * Default Value setter
   * 
   * @param string $defaulValue
   * @return Column
   * @throws InvalidArgumentException
   * @throws RuntimeException
   */
  public function setDefaultValue(string $defaultValue): Column
  {

    if (!in_array($this->type, static::COLUMN_WITH_DEFAULT_VALUE))
      throw new InvalidArgumentException("Default value is not supported on column type {$this->type}");

    if ($defaultValue!==null && $this->autoIncrement)
      throw new RuntimeException('Cannot add a default value to an Auto Increment');

    $this->defaultValue = $defaultValue;

    return $this;
  }

  /**
   * Comment setter
   * 
   * @param string $comment
   * @return Column
   */
  public function setComment(string $comment): Column
  {

    $this->comment = $comment;

    return $this;
  }

  /**
   * Auto Increment setter
   * 
   * @param bool $autoIncrement
   * @return Column
   * @throws InvalidArgumentException
   */
  public function setAutoIncrement(bool $autoIncrement): Column
  {

    if (!in_array($this->type, static::COLUMN_WITH_AUTO_INCREMENT))
      throw new InvalidArgumentException("Auto Increment is not supported on column type {$this->type}");

    $this->autoIncrement = $autoIncrement;
    $this->defaultValue = false;
    $this->unsigned = true;

    return $this;
  }

  /**
   * Nullable setter
   * 
   * @param bool $nullable
   * @return Column
   */
  public function setNullable(bool $nullable): Column
  {

    $this->nullable = $nullable;

    return $this;
  }

  /**
   * Unique setter
   * 
   * @param bool $unique
   * @return Column
   */
  public function setUnique(bool $unique): Column
  {

    $this->unique = $unique;

    return $this;
  }

  /**
   * Index setter
   * 
   * @param bool $index
   * @return Column
   */
  public function setIndex(bool $index): Column
  {

    $this->index = $index;

    return $this;
  }

  /**
   * Unsigned setter
   * 
   * @param bool $unsigned
   * @return Column
   * @throws InvalidArgumentException
   * @throws RuntimeException
   */
  public function setUnsigned(bool $unsigned): Column
  {

    if (!in_array($this->type, static::COLUMN_WITH_UNSIGNED))
      throw new InvalidArgumentException("Unsigned is not supported on column type {$this->type}");

    if (!$unsigned && $this->autoIncrement)
      throw new RuntimeException('Cannot set an Auto Increment column to signed');

    $this->unsigned = $unsigned;

    return $this;
  }

  /**
   * Validates column name will work
   * 
   * @param string $name
   * @return bool
   */
  public static function validateName(string $name): bool
  {
    return preg_match('/^([[:alpha:]_][[:alnum:]_]*|("[^"]*")+)$$/', $name);
  }
}
