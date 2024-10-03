<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string $table = 'usuarios';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id();

        $build->int('id_acesso')->index('idx_acesso');
        $build->varchar('nome');
        $build->varchar('email')->index('idx_email');
        $build->boolean('ativo')->after('id_acesso');

        $build->id('primary_test');

        $build->dates();
    }

    public function down(Builder $build)
    {
        $build->dropTable();
    }
};
