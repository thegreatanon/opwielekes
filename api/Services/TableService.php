<?php

require_once(__DIR__ . "/TableEnum.php");
require_once(__DIR__ . "/../pdoconnect.php");

class TableService {

    /**
     * Get the correct table name - with location prefix
     **/
    public static function getTable($tableName) {
        if (!TableEnum::isValidValue($tableName)) {
            throw new Exception("table does not exist");
        }

        if (isset($_SESSION["login"]) && $_SESSION["login"] == "demo") {
            return 'dem_' . $tableName;
        } else if (isset($_SESSION["login"]) && $_SESSION["login"] == "ledeberg") {
            return 'led_' . $tableName;
        } else if (isset($_SESSION["login"]) && $_SESSION["login"] == "moscou") {
            return 'mos_' . $tableName;
        } else {
            return $tableName;
        }
    }

}