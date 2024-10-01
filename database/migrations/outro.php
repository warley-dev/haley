<?php

use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    public string $table = 'outro';
    public string|null $connection = null;

    public function up(Builder $build)
    {
        // $build->dropTable(['test','outro','aaa']);

        $build->id();

        $build->int('int')->nullable(false);
        $build->double('double')->nullable(true);
        $build->float('float')->nullable(true);
        $build->decimal('decimal')->nullable(true);
        $build->boolean('boolean')->nullable(false);

        $build->varchar('varchaae')->nullable(true);
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

        // $build->index(['varchar',  'nome', 'email']);
        // $build->foreign('int', 'outro', 'id')->onDelete('CASCADE')->onUpdate('CASCADE')->name('fk_teste');


        // continuar:
        // testar indexs / add and drop (dropar index adicionais)
        // bigint

        $build->dropConstrant('fk_teste');
        // $build->dropConstrant('unique_teste');
        // $build->dropColumn(['email','int','nome']);
        // $build->dropTable();
        // $build->rename('varchaae','teste_rename');
    }

    public function down() {}
};
