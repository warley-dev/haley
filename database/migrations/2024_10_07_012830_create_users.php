<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string|null $table = 'users';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id();

        $build->varchar('name')->index();
        $build->varchar('email')->unique()->index();
        $build->varchar('password');
        $build->boolean('active');

        $build->dates();
    }

    public function down(Builder $build)
    {
        $build->dropTables();
    }
};