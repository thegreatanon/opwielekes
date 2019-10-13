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

        if (isset($_SESSION["login"]) && isset($_SESSION["dbcode"])) {
            return $_SESSION["dbcode"] . '_' . $tableName;
        } else {
            return $tableName;
        }

    }

}
