<?php

namespace BlackBrickSoftware\MigrationBuilder;

use ArrayObject;

class Columns extends ArrayObject
{
  public function offsetSet($key, $val): void
  {
    if ($val instanceof Column) {
      parent::offsetSet($key, $val);
      return;
    }
    throw new \InvalidArgumentException('Must be a Column type');
  }
}
