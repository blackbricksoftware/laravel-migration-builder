<?php

namespace BlackBrickSoftware\LaravelMigrationBuilder;

use ArrayObject;

class ForeignKeys extends ArrayObject
{
  public function offsetSet($key, $val): void
  {
    if ($val instanceof ForeignKey) {
      parent::offsetSet($key, $val);
      return;
    }
    throw new \InvalidArgumentException('Must be a ForeignKey type');
  }
}
