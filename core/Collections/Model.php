<?php

namespace Haley\Collections;

use Haley\Database\DB;

abstract class Model
{
    protected static string|null $connection = null;
    public static string $table;
    protected static string|null $id = null;
    protected static array $fillable = [];

    public static function query()
    {
        return DB::table(static::$table)->connection(static::$connection)->table(static::$table);
    }

    public static function create(array $data)
    {
        if (!is_array($data[array_key_first($data)])) {
            foreach ($data as $column => $value) if (!in_array($column, static::$fillable)) unset($data[$column]);
        } else {
            foreach ($data as $key_data => $values) {
                foreach ($values as $column => $value) {
                    if (!in_array($column, static::$fillable)) unset($data[$key_data][$column]);
                }
            }
        }

        return DB::table(static::$table)->connection(static::$connection)->insert($data);
    }

    /**
     * @return int|array
     */
    public static function createGetId(array $data)
    {
        if (!is_array($data[array_key_first($data)])) {
            foreach ($data as $column => $value) if (!in_array($column, static::$fillable)) unset($data[$column]);

            return (int)DB::table(static::$table)->connection(static::$connection)->insertGetId($data);
        } else {
            $ids = [];

            foreach ($data as $key_data => $values) {
                foreach ($values as $column => $value) {
                    if (!in_array($column, static::$fillable)) unset($data[$key_data][$column]);
                }

                $ids[] = (int)DB::table(static::$table)->connection(static::$connection)->insertGetId($data[$key_data]);
            }

            return $ids;
        }
    }

    public static function createOrIgnore(array $data)
    {
        if (!is_array($data[array_key_first($data)])) {
            foreach ($data as $column => $value) if (!in_array($column, static::$fillable)) unset($data[$column]);
        } else {
            foreach ($data as $key_data => $values) {
                foreach ($values as $column => $value) {
                    if (!in_array($column, static::$fillable)) unset($data[$key_data][$column]);
                }
            }
        }

        return DB::table(static::$table)->connection(static::$connection)->insertIgnore($data);
    }

    public static function update(string|array $id, array $data)
    {
        if (!is_array($id)) $id = [$id];

        return DB::table(static::$table)->connection(static::$connection)->whereIn(static::$id, $id)->update($data);
    }

    public static function updateOrCreate(array $check, array $data)
    {
        foreach ($data as $column => $value) if (!in_array($column, static::$fillable)) unset($data[$column]);

        $has = DB::table(static::$table)->connection(static::$connection)->select(static::$id);

        foreach ($check as $column => $value) {
            if (is_array($value)) $has->whereIn($column, $value);
            else $has->where($column, $value);
        }

        $has = $has->get();

        if (count($has)) {
            $ids = [];

            foreach ($has as $value) $ids[] = self::toArray($value)[static::$id];

            if (count($ids)) return DB::table(static::$table)->connection(static::$connection)->whereIn(static::$id, $ids)->update($data);
        } else {
            return DB::table(static::$table)->connection(static::$connection)->insert($data);
        }

        return 0;
    }

    public static function delete(int|string|array $id)
    {
        if (!is_array($id)) $id = [$id];

        return DB::table(static::$table)->connection(static::$connection)->whereIn(static::$id, $id)->delete();
    }

    public static function id(int|string $id, string|array $columns = '*')
    {
        return DB::table(static::$table)->connection(static::$connection)->select($columns)->where(static::$id, $id)->first();
    }

    public static function all()
    {
        return DB::table(static::$table)->connection(static::$connection)->get();
    }

    public static function count()
    {
        return DB::table(static::$table)->connection(static::$connection)->count();
    }

    private static function toArray(mixed $value)
    {
        if (is_object($value)) return get_object_vars($value);

        return $value;
    }
}
