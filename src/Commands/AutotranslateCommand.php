<?php

namespace BernskioldMedia\Autotranslate\Commands;

use Illuminate\Console\Command;

class AutotranslateCommand extends Command
{
    public $signature = 'laravel-autotranslate';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
