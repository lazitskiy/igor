<?php
declare(strict_types=1);

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class ModelBase extends Model
{
    protected $hidden = [];

    public function getTable(): string
    {
        return $this->table ?? Str::snake(Str::studly(class_basename($this)));
    }

    public static function getTableStatic(): string
    {
        return Str::snake(Str::studly(class_basename(get_called_class())));
    }

    public function newEloquentBuilder($query)
    {
        $className = static::class . 'QueryBuilder';

        if (!class_exists($className)) {
            $className = Builder::class;
        }

        return new $className($query);
    }

    public function newCollection(array $models = [])
    {
        $className = static::class . 'Collection';

        if (!class_exists($className)) {
            $className = Collection::class;
        }

        return new $className($models);
    }
}
