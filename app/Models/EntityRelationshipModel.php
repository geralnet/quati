<?php
declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EntityRelationshipModel extends Model {
    const DATABASE_NAMESPACE_PREFIX = 'App\\Models\\';

    public function getTable() {
        if (isset($this->table)) {
            return $this->table;
        }

        $class = get_class($this);
        if (strpos($class, self::DATABASE_NAMESPACE_PREFIX) === 0) {
            $class = substr($class, strlen(self::DATABASE_NAMESPACE_PREFIX));
            $class = explode('\\', $class);
            $class[] = Str::plural(array_pop($class));
            $this->table = strtolower(implode('_', $class));
        }
        else {
            $this->table = parent::getTable();
        }

        return $this->table;
    }
}
