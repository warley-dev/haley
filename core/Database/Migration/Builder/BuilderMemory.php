<?php

namespace Haley\Database\Migration\Builder;

class BuilderMemory
{
    public static string|null $connection = null;
    public static array|null $config = null;

    public static string|null $table = null;

    public static array $id = [];
    public static array $columns = [];
    public static array $constraints = [];

    public static array $rename = [];
    public static array $foreign = [];
    public static array $index = [];

    public static array $dropTable = [];
    public static array $dropIndex = [];
    public static array $dropColumn = [];
    public static array $dropConstraint = [];

    public static function addColumn(string $name, string $type, int|string|array|null $paramns = null)
    {
        if (in_array(self::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
            if ($paramns) $type .= "($paramns)";

            self::$columns[] = [
                'name' => $name,
                'type' => $type,
                'options' => [
                    'NULLABLE' => true,
                    'DEFAULT' => null,
                    'ONUPDATE' => null,
                    'COMMENT' => null,
                    'AUTOINCREMENT' => false,
                    'POSITION' => null,
                    'PRIMARY' => false
                ]
            ];
        }
    }

    static public function compileForeigns()
    {
        foreach (self::$foreign as $value) {
            $name = $value['name'];
            $column = $value['column'];
            $reference_table = $value['reference_table'];
            $reference_column = $value['reference_column'];
            $on_delete = $value['on_delete'];
            $on_update = $value['on_update'];

            if (in_array(self::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
                $value = sprintf('(`%s`) REFERENCES `%s`(`%s`)', $column, $reference_table, $reference_column);

                if ($on_delete !== null) $value .= ' ' . $on_delete;
                if ($on_update !== null) $value .= ' ' . $on_update;
                if ($name == null) $name = sprintf('fk_%s_%s', self::$table, $column);

                self::addConstraint($name, 'FOREIGN KEY', trim(preg_replace('/( ){2,}/', '$1', $value)));
            }
        }
    }

    static public function getColumns()
    {
        $columns = [];

        if (!empty(self::$columns)) {
            foreach (self::$columns as $key => $value) {
                if (in_array($value['name'], self::$rename)) continue;

                $columns[$key] = $value;
            }
        }

        return $columns;
    }

    static public function addConstraint(string $name, string $type, string $value)
    {
        self::$constraints[] = [
            'name' => $name,
            'type' => $type,
            'value' => $value
        ];
    }

    static public function reset()
    {
        self::$connection = null;
        self::$config = null;
        self::$table = null;
        self::$id = [];
        self::$columns = [];
        self::$constraints = [];
        self::$rename = [];
        self::$foreign = [];
        self::$index = [];
        self::$dropTable = [];
        self::$dropColumn = [];
        self::$dropConstraint = [];
    }
}
