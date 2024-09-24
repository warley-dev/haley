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
            $build->id('id');

            $build->int('int')->nullable(false);
            $build->varchar('varchar');
            $build->json('json');
            $build->text('text');
            $build->timestamp('timestamp');
            $build->date('date');
            $build->datetime('datetime');
            $build->year('year');
            $build->time('time');
            $build->double('double');
            $build->float('float');
            $build->decimal('decimal');
            $build->boolean('boolean');


            // $build->varchar('nome')->comment('helo word')->nullable(false)->default('aaaa')->unique();
            // $build->varchar('email')->comment('helo word')->nullable(true);


            $build->dates();

            $build->dropColumn('email');
            $build->dropColumn(['nome', 'id']);
        });
    }
};
