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

      


        $build->int('teste_update')->primaryKey()->outoIncrement()->after('id')->comment('helo word')->default(0)->nullable(false);
    }

    public function down(Builder $build)
    {
        $build->dropTable();
    }
};