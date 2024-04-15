<?php

use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    public bool $active = true;
    public bool $single = true;

    public function up()
    {
        (new Migration)->up('haley', function (Builder $build) {
            $build->id('id', 'dfg');
            // $build->int('id');
            $build->varchar('nome')->comment('helo word')->nullable(false);
            $build->varchar('email')->comment('helo word')->nullable(true);
            $build->json('json')->comment('aff');

            $build->foreign('nome','testa','nome');








            $build->int('fore')->comment('foreing test');

            //  $build->foreign('fore', 'teste', 'id');

            // $build->rename('test','mudou');

            // $build->varchar('varcfghar')->comment('test fdgsdfg')->default('sdgfsdfg sdfgsdfg')->notNull()->unique();
            // $build->varchar('varcfgr')->comment('vc')->default('v dfgdfc');
        });
    }
};
