<?php

/**
 * Loosly based on: https://github.com/Agontuk/schema-builder/blob/master/src/Migrations/MigrationCreator.php
 */

namespace BlackBrickSoftware\MigrationBuilder;

use Illuminate\Support\Str;

class Migration extends Base
{

  /**
   * @var string table name
   */
  protected string $name;

  /**
   * @var string path
   */
  protected string $path;

  /**
   * @var Table
   */
  protected Table $table;

  /**
   * The migration creator instance.
   *
   * @var MigrationCreator
   */
  protected MigrationCreator $creator;

  /**
   * Migration constructor.
   *
   * @param string $name
   * @param string $path
   * @param Table $table
   * @param MigrationCreator $creator
   * @param  \Illuminate\Support\Composer  $composer
   * @return void
   */
  public function __construct(string $name, string $path, Table $table, MigrationCreator $creator)
  {

    $this->setName($name);
    $this->setPath($path);
    $this->table = $table;
    $this->creator = $creator;
  }

  /**
   * Name setter
   * 
   * @param string $name
   * @return Migration
   * @throws InvalidArgumentException
   */
  public function setName(string $name): Migration
  {

    $this->name = Str::snake(trim($name));

    return $this;
  }

  /**
   * Path setter
   * 
   * @param string $path
   * @return Migration
   * @throws InvalidArgumentException
   */
  public function setPath(string $path): Migration
  {

    $this->path = $path;

    return $this;
  }

  /**
   * Write the migration file to disk.
   *
   * @param  string  $name
   * @param  string  $table
   * @param  bool  $create
   * @return string
   */
  public function writeMigration(bool $create): string
  {

    $file = $this->creator->create(
      $this->name,
      $this->path,
      $this->table->name,
      $this->buildColumns(),
      $this->buildSpecialColumns(),
      $this->buildForeignKeys(),
      $create
    );

    return $file;
  }

  /**
   * Process table and generate column commands
   * 
   * @return string
   */
  public function buildColumns(): string
  {
    $commands = [];

    $columns = $this->table->columns;
    if (empty($columns))
      return '';

    foreach ($columns as $column)
      $commands[] = str_repeat(' ', 12) . $this->buildColumn($column);

    return implode("\n", $commands);
  }

  /**
   * Parse a Column into a creation command
   *
   * @param Column $column
   *
   * @return string
   */
  protected function buildColumn(Column $column): string
  {

    $cmd = '';

    $name = $column->name;
    $type = $column->type;

    if ($column->autoIncrement) {
      // Change integer/bigInteger to increments/bigIncrements
      $type = str_replace('integer', 'increments', $type);
      $type = str_replace('Integer', 'Increments', $type);
      $cmd .= sprintf('$table->%s(\'%s\')', $type, $name);
    } else {
      $cmd .= sprintf('$table->%s(\'%s\'', $type, $name);
      $length = $column->length;
      if ($length !== null)
        $cmd .= ',' . $length;
      $fractional = $column->fractional;
      if ($fractional !== null)
        $cmd .= ',' . $fractional;
      $cmd .= ')';
    }

    // Default value check
    $defaultValue = $column->defaultValue;
    if ($defaultValue !== null) {
      if (is_numeric($defaultValue)) {
        $cmd .= sprintf('->default(%s)', (int) $defaultValue);
      } elseif (strlen($defaultValue)) {
        $cmd .= sprintf('->default(\'%s\')', $defaultValue);
      }
    }

    // Nullable check
    if ($column->nullable)
      $cmd .= '->nullable()';

    // Unique check
    if ($column->unique)
      $cmd .= '->unique()';

    // Index check
    if ($column->index)
      $cmd .= '->index()';

    // Unsigned check
    if ($column->unsigned)
      $cmd .= '->unsigned()';

    // Comment check
    if ($column->comment !== null)
      $cmd .= sprintf('->comment(\'%s\')', $column->comment);

    // End of statement
    $cmd .= ';';

    return $cmd;
  }

  /**
   * Process table and generate special columns
   * 
   * @return string
   */
  public function buildSpecialColumns(): string
  {
    $commands = [];

    if ($this->table->softDelete)
      $commands[] = str_repeat(' ', 12) . '$table->softDeletes();';

    if ($this->table->timestamps)
      $commands[] = str_repeat(' ', 12) . '$table->timestamps();';

    return implode("\n", $commands);
  }

  /**
   * Process table and generate column commands
   * 
   * @return string
   */
  public function buildForeignKeys(): string
  {
    $commands = [];

    $foreignKeys = $this->table->foreignKeys;
    if (empty($foreignKeys))
      return '';

    foreach ($foreignKeys as $foreignKey)
      $commands[] = str_repeat(' ', 12) . $this->buildForeignKey($foreignKey);

    return implode("\n", $commands);
  }

  /**
   * Parse a foreign key into a creation command
   *
   * @param ForeignKey $foreignKey
   *
   * @return string
   */
  protected function buildForeignKey(ForeignKey $foreignKey): string
  {

    $cmd = '';

    // basic foreign key structure
    $cmd .= sprintf(
      '$table->foreign(\'%s\')->references(\'%s\')->on(\'%s\')',
      $foreignKey->column,
      $foreignKey->references,
      $foreignKey->on
    );

    if ($foreignKey->onDelete !== null)
      $cmd .= sprintf('->onDelete(\'%s\')', $foreignKey->onDelete);

    // End of statement
    $cmd .= ';';

    return $cmd;
  }
}
