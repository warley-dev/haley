<?php

use Haley\Database\DB;
use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    public bool $active = true;
    public bool $single = true;

    public function up()
    {
        (new Migration)->up('test', function (Builder $build) {
            $build->id();

            $build->int('int')->nullable(false)->index();
            $build->double('double')->nullable(true);
            $build->float('float')->nullable(true);
            $build->decimal('decimal')->nullable(true);
            $build->boolean('boolean')->nullable(true);

            $build->varchar('varchar')->nullable(true);
            $build->text('text')->nullable(true);
            $build->json('json')->nullable(true);

            $build->timestamp('timestamp')->nullable(true);
            $build->date('date')->nullable(true);
            $build->datetime('datetime')->nullable(true);
            $build->year('year')->nullable(true);
            $build->time('time')->nullable(true);
            $build->dates();

            $build->set('set', ['um', 'dois', 'tres'])->nullable(true);
            $build->set('enum', ['um', 'dois', 'tres'])->nullable(true);

            $build->varchar('nome')->comment('helo word')->nullable(true)->default('aaaa')->unique('unique_teste');
            $build->varchar('email')->comment('helo word')->nullable(true);

            // $build->foreign('int','outro','id')->onDelete('CASCADE')->onUpdate('CASCADE')->name('fk_teste');

            // continuar:
            // indices, como utilizar indices e adicionar try caths
            // CREATE INDEX idx_select ON pedidos_produtos(id_produto, id_pedido);

            // index default name (idx_select)
            // drop index
            // bigint

            // $build->dropConstrant('fk_teste');
            // $build->dropConstrant('unique_teste');
            // $build->dropColumn('email');
            // $build->dropTable();
        });
    }
};
