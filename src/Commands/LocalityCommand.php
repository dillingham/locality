<?php

namespace Dillingham\Locality\Commands;

use Illuminate\Console\Command;

class LocalityCommand extends Command
{
    public $signature = 'locality';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
