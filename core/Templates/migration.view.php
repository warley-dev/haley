<?php


use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = {{$table ? "'" . strtolower($table) . "'" : 'null'}};
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id();

        $build->dates();
    }

    public function down(Builder $build)
    {
        $build->dropTables();
    }
};
