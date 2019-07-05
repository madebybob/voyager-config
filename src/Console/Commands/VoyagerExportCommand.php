<?php

namespace MadeByBob\VoyagerConfig\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use MadeByBob\VoyagerConfig\Core\VoyagerExport;

class VoyagerExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '
        voyager:export 
        {--f|force : Does not request any permission} {--y|yes : Does not request any permission}
        {--skip=* : The tables to skip}
        {--only=* : The tables only to export}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Voyager\'s data into the config folder';

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

        $tables = config('voyager-config.tables');

        foreach($tables as $table) {
            if(!$this->checkTable($table)) continue;

            if(!$this->requestConfirmation($table)) continue;

            VoyagerExport::forTable($table);
            
            $this->info("Voyager Config Export for table `$table` is done.");      
        }

        $this->success('Congratulations! Voyager Config Export is done!');
    }

    /**
     * Checks if the table should be exported according to the `only` option and `skip` option
     * 
     * @return boolean
     */
    protected function checkTable($table) {
        if($this->option('only')) {
            return in_array($table, $this->option('only'));
        }

        return !in_array($table, $this->option('skip'));
    }

    /**
     * Requests confirmation to the user
     * If the `force` or `yes` option is used, no confirmation will be requested
     * 
     * @return boolean
     */
    protected function requestConfirmation($table) {
        if(!$this->option('force') && !$this->option('yes')) {
            $permission = $this->confirm("Are you sure you want to start the Voyager Config Export for table `$table`?");

            if(!$permission) {
                $this->error("Skipped Voyager Config Export for table `$table`.");
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
