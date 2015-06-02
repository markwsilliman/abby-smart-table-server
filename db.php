<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.php";

class db {
    /** @var mysqli**/
    private $conn;

    //__construct
    public function __construct() {
        echo "here";
        echo config::val("db_ip");
        $this->conn = mysqli_connect(config::val("db_ip"),config::val("db_user"),config::val("db_pass"),"smarttable") or die("mysqli_connect 1");
        echo "hereb";
        $this->create_tables();
    }
    //end __construct

    //create_tables
    private function create_tables() {
        $query = "CREATE TABLE IF NOT EXISTS touch (
                    ID BIGINT AUTO_INCREMENT,
                    x_percent DOUBLE,
                    y_percent DOUBLE,
                    date_created DATETIME,
                    PRIMARY KEY  (ID)
                )";
        $this->query($query);
    }
    //end create_tables

    //query
    public function query($sql) {
        return $this->conn->query($sql) or die("mysqli query 1 [" . htmlspecialchars($sql) . "]");
    }
    //end query

    //fetch_array
    public function fetch_array($result) {
        return $result->fetch_array(MYSQLI_ASSOC);
    }
    //end fetch_array


}