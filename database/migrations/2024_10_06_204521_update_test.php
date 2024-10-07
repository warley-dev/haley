<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = 'test';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        // $build->id();
        // $build->dates();

        //  $build->varchar('teste_update');


        $build->id('id');

        $build->varchar('edite_create_test')->comment('comment aa')->after('int')->nullable(false)->unique()->default('test');
    }

    public function down(Builder $build)
    {
        $build->dropTables();
    }
};