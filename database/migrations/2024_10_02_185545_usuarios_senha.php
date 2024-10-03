<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string $table = 'usuarios';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->varchar('senha');
    }

    public function down(Builder $build)
    {
        $build->dropColumn('senha');
    }
};
