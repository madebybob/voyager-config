<?php

namespace MadeByBob\VoyagerConfig\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MadeByBob\VoyagerConfig\Core\VoyagerExport;

class VoyagerClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:clear {--f|force} {--y|yes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears Voyager\'s data from the config folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Voyager Config Export will start');

        if($this->requestConfirmation()) {
            VoyagerExport::clear();
        }

        $this->success('Congratulations! Voyager Config Export is done!');
    }

    /**
     * Requests confirmation to the user
     * If the `force` or `yes` option is used, no confirmation will be requested
     * 
     * @return boolean
     */
    protected function requestConfirmation() {
        if(!$this->option('force') && !$this->option('yes')) {
            $permission = $this->confirm("Are you sure you want to clear the Voyager Config Export's data?");

            if(!$permission) {
                $this->error("The data will not be cleared");
                return false;
            }
        }

        return true;
    }

    /**
     * Shorthand for an output with green background and black foreground
     * 
     * @return void
     */
    protected function success($text) {
        $this->line("<bg=green;fg=black>$text</>");      
    }
}
