<?php

require_once(__DIR__ . "/TableEnum.php");
require_once(__DIR__ . "/../pdoconnect.php");

class TableService {

    /**
     * Get the correct table name - with location prefix
     **/
    public static function getTable($tableName, $accountcode=null) {

        if (!TableEnum::isValidValue($tableName)) {
            throw new Exception("table does not exist");
        }

        if ($tableName == "postalcodes" ) {
          return $tableName;
        }
    
        if (isset($accountcode)) {
            return $accountcode . '_' . $tableName;
        } else {
          if (isset($_SESSION["account"])) {
              return $_SESSION["account"]["AccountCode"] . '_' . $tableName;
          } elseif (isset($_SESSION["urlaccount"])) {
              return $_SESSION["urlaccount"]["AccountCode"] . '_' . $tableName;
          } else {
              return $tableName;
          }
        }
    }


}
