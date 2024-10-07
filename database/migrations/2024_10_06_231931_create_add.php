<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = 'fore';
    public string|null $connection = null;

    public function up(Builder $build)
    {


        $build->dropIndexs('idx_teste');
        $build->dropConstrant('fore_delete');
        // $build->dropColumns('teste');


        // $build->rename('teste','teste_renomeado');
    }

    public function down(Builder $build)
    {
        $build->dropTables();
    }
};