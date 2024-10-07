<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = 'fore';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id();
        $build->int('fore');

        $build->varchar('teste')->index('idx_teste');

        $build->foreign('fore', 'test', 'id')->onDelete()->onUpdate()->name('fore_delete');

        $build->dates();
    }

    public function down(Builder $build)
    {
        $build->dropTables();
    }
};