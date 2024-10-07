<?php

namespace Haley\Database\Migration\Builder;

class BuilderMemory
{
    public static string|null $connection = null;
    public static array|null $config = null;
    public static string|null $table = null;

    public static string|null $id = null;
    public static array $columns = [];
    public static array $constraints = [];

    public static array $renames = [];
    public static array $foreigns = [];
    public static array $indexs = [];

    public static array $dropTables = [];
    public static array $dropIndexs = [];
    public static array $dropColumns = [];
    public static array $dropConstraints = [];

    public function __construct(string $connection, array $config, string|null $table)
    {
        self::$connection = $connection;
        self::$config = $config;
        self::$table = $table;
    }

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
                    'POSITION' => null
                ]
            ];
        }
    }

    static public function compileForeigns()
    {
        foreach (self::$foreigns as $value) {
            $name = $value['name'];
            $column = $value['column'];
            $reference_table = $value['reference_table'];
            $reference_column = $value['reference_column'];
            $on_delete = $value['on_delete'];
            $on_update = $value['on_update'];


            if (in_array(self::$config['driver'], ['mysql', 'pgsql', 'mariadb'])) {
                $value = sprintf('(%s) REFERENCES %s(%s)', self::quotes($column), self::quotes($reference_table), self::quotes($reference_column));

                if ($on_delete !== null) $value .= ' ' . $on_delete;
                if ($on_update !== null) $value .= ' ' . $on_update;
                if ($name == null) $name = sprintf('foreign_%s', $reference_table);

                self::addConstraint($name, 'FOREIGN KEY', trim(preg_replace('/( ){2,}/', '$1', $value)));
            }
        }
    }

    static public function getColumns()
    {
        $columns = [];

        if (!empty(self::$columns)) {
            foreach (self::$columns as $key => $value) {
                if (in_array($value['name'], self::$renames)) continue;

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

    static private function quotes(string $string)
    {
        $string = preg_replace('/\b(?!as\b)(\w+)\b/i', BuilderMemory::$config['quotes'] . '$1' . BuilderMemory::$config['quotes'], $string);
        $string = preg_replace('/(' . preg_quote(BuilderMemory::$config['quotes']) . ')\s/', '$1 ', $string);

        return $string;
    }

    static public function reset()
    {
        self::$connection = null;
        self::$config = null;
        self::$table = null;
        self::$id = null;
        self::$columns = [];
        self::$constraints = [];
        self::$renames = [];
        self::$foreigns = [];
        self::$indexs = [];
        self::$dropTables = [];
        self::$dropColumns = [];
        self::$dropConstraints = [];
    }
}
