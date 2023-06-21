<?php

use Haley\Database\Migration\Builder\Builder;
use Haley\Database\Migration\Migration;

return new class
{
    /**
     * Se 
     */
    public bool $single = false;

    public function up()
    {
        (new Migration)->up('haley', function (Builder $build) {
            $build->id('id', 'dfg');
            $build->varchar('mudou')->comment('helo word')->nullable(true);
        





            $build->int('fore')->nullable(false)->comment('foreing test');

             $build->foreign('fore', 'filmes', 'id');

            // $build->rename('test','mudou');

            // $build->varchar('varcfghar')->comment('test fdgsdfg')->default('sdgfsdfg sdfgsdfg')->notNull()->unique();
            // $build->varchar('varcfgr')->comment('vc')->default('v dfgdfc');
        });
    }
};
