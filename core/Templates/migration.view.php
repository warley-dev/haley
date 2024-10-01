<?php


use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string $table = '{{ $table }}';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        $build->id();

        $build->dates();
    }

    public function down(Builder $build) {}
};