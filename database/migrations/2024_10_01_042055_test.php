<?php

use Haley\Database\Migration\Builder\Builder;

return new class
{
    public string $table = 'test';
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

        $build->varchar('varchaaeres')->nullable(true);
        $build->text('text')->nullable(true);
        $build->json('json')->nullable(true);

        $build->timestamp('timestamp')->nullable(true);
        $build->date('date')->nullable(true);
        $build->datetime('datetime')->nullable(true);
        $build->year('year')->nullable(true);
        $build->time('time')->nullable(true);
        $build->dates();

        // $build->set('set', ['um', 'dois', 'tres'])->nullable(true);
        // $build->enum('enum', ['um', 'dois', 'tres'])->nullable(true);

        // testar comment nas em postgrs

        $build->varchar('nome')->nullable(true)->default('aaaa')->unique('unique_teste');
        $build->varchar('email')->nullable(true);

        // $build->index(['varchar',  'nome', 'email']);
        $build->foreign('int', 'outro', 'id')->onDelete('CASCADE')->onUpdate('CASCADE')->name('fk_teste');


        // continuar:
        // testar indexs / add and drop (dropar index adicionais)
        // bigint

        // $build->dropConstrant('fk_teste');
        // $build->dropConstrant('unique_teste');
        // $build->dropColumn(['email','int','nome']);
        // $build->dropTable();
        // $build->rename('varchaae','teste_rename');
    }

    public function down(Builder $build)
    {
        $build->dropTable();
    }
};
