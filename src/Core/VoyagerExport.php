<?php

namespace MadeByBob\VoyagerConfig\Core;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VoyagerExport {

  /**
   * Does the export job: get all data and save into the right file
   * 
   * @return void
   */
  public static function forTable($table) {
    $data = self::exportToJson($table);

    self::saveData($table, $data);
  }

  /**
   * Gets all data from the given table
   * 
   * @return array
   */
  public static function exportToJson($table) {

    $data = DB::table($table)->get()->all();
    return $data;
  }

  /**
   * Saves all received data to the config path file with the tables' name as filename
   * 
   * @return void
   */
  public static function saveData($table, $data) {
    $driver = self::getDriver();
    $folder = config('voyager-config.folder');

    $driver->put("$folder/$table.json", json_encode($data, JSON_PRETTY_PRINT));
  }

  /**
   * Removes al the Voyager Config files
   * 
   * @return void
   */
  public static function clear() {
    $driver = self::getDriver();
    $folder = config('voyager-config.folder');

    $driver->deleteDirectory($folder);
  }

  /**
   * Returns a driver with the config path as root
   * 
   * @return Storage
   */
  protected static function getDriver() {
    $driver = Storage::createLocalDriver([
      'root' => config('voyager-config.path')
    ]);
    return $driver;
  }

}