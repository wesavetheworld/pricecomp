<?php
/**
 * Created by PhpStorm.
 * User: mhague
 * Date: 01/09/2014
 * Time: 12:08
 */

namespace classes;

use DB;

class Utils
{
    public static function updateEntry($entry, $table, $primaryKey = 'id')
    {
        $id = 0;
        $values = null;

        foreach ($entry as $key => $value) {
            if ($key == $primaryKey) {
                $id = $value;
            } else {
                $values[$key] = $value;
            }
        }

        return DB::table($table)->where($primaryKey, '=', $id)->update($values);
    }

    public static function deleteEntry($entry, $table)
    {
        $id = 0;
        $values = null;

        foreach ($entry as $key => $value) {
            if ($key == 'id') {
                $id = $value;
            } else  {
                $values[$key] = $value;
            }
        }

        return DB::table($table)->where('id', '=', $id)->update($values);
    }

    public static function insertEntry($entry, $table)
    {
        return DB::table($table)->insertGetId((array) $entry);
    }

    public static function tableCount($table)
    {
        return DB::table($table)->count();
    }
} 