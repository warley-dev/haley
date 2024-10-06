<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = 'test';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id('outro_id');

        // $build->dates();
    }

    public function down(Builder $build)
    {
        $build->dropTable();
    }
};