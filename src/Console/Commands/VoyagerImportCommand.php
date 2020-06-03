<?php

namespace MadeByBob\VoyagerConfig\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VoyagerImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voyager:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all data from the config folder into the Voyager related tables.';

    /**
     * The list of tables ordered for Foreign Key checks compliance.
     *
     * @var array
     */
    protected $tables = [
        'data_types',
        'data_rows',
        'roles',
        'permissions',
        'permission_role',
        'menus',
        'menu_items',
        'settings',
        'translations',
    ];

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
        DB::beginTransaction();

        $this->info("Starting Voyager config import ...");

        foreach ($this->tables as $table) {
            $this->line("Importing {$table}...");

            // get configuration files created by `voyager:export`
            $conf_entries = $this->getConfigurationEntries($table);

            // check valid config entries 
            if (empty($conf_entries)) {
                $this->info("DB changes are reverted.");
                // stop importing configuration.
                return;
            }

            // execute insert on successful parsing
            DB::table($table)->insertOrIgnore( $conf_entries );
        }

        // Commit changes on DB. Errors will automatically reverted by uncommitted transaction
        DB::commit();

        $this->info("Importing Voyager configuration successful!");
    }

    /**
     * Get the contents of the table configuration file.
     * 
     * @return string
     */
    protected function getConfigFileContent($file_path) {
        // get configuration files created by `voyager:export`
        $json_content = @file_get_contents($file_path);

        // display error if file access was not successful
        if ($json_content === false) {
            $this->info("Importing Voyager configuration failed!");
            $this->error('File "' . $file_path . '" could not be accessed. Check the path and permission.');
            // return empty string
            return '';
        }

        // return file content
        return $json_content;
    }

    /**
     * Parse table configuration for DB insert.
     * 
     * @return array
     */
    protected function parseConfigFileContent($json_content) {
        // decode json file as array
        $conf_entries = json_decode($json_content, true);
        // display error if decoding has error message
        if (json_last_error()) {
            $this->info("Importing Voyager configuration failed!");
            $this->error(
                'JSON Decoding error: ' . json_last_error_msg()
            );

            // return empty array
            return [];
        }

        // return parsed content
        return $conf_entries;
    }

    /**
     * Get the contents of the table configuration and parse for DB insert.
     * 
     * @return array
     */
    protected function getConfigurationEntries($table) {
        // generate file path created by `voyager:export`
        $file_path = 'config/voyager/' . $table . '.json';
        // pass current configuration path
        $json_content = $this->getConfigFileContent($file_path);
        // exit if invalid file
        if (empty($json_content)) return [];
        // decode json file as array
        $conf_entries = $this->parseConfigFileContent($json_content);

        return $conf_entries;
    }
}
