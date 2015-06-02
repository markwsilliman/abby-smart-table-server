<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/db.php";

if(isset($_GET['touch_report_print'])) {
    $tr = new touch_report();
    $tr->print_report();
}

class touch_report {
    private $output;
    private $most_recent_object = false;

    //__construct
    public function __construct() {

        $db = new db();
        $this->most_recent_object = false;
        $result = $db->query("select * from touch order by ID desc limit 100");
        $output = "";
        $output .= "<table border='1'>";
        $output .= "<tr><th>ID</th><th>X</th><th>Y</th><th>TYPE</th><th>DATE</th></tr>";
        $ups = array();
        $downs = array();
        $ups_index = 0;
        $downs_index = 0;
        while($row = $db->fetch_array($result)) {
            if($row['type_percent'] == 2) {
                $ups[$ups_index] = $row['x_percent'] . "+" . $row['y_percent']; //TODO really this should take into account the time up to 1 second later
                $ups_index++;
            }
            $output .= "<tr ";
            if($row['type_percent'] == 1) {
                //this is a down so check is a late up canceled it out
                if(in_array($row['x_percent'] . "+" . $row['y_percent'],$ups)) {
                    $output .= "style='background-color: red;'";
                    unset($ups[array_search($row['x_percent'] . "+" . $row['y_percent'],$ups)]);
                }
                else {
                    $output .= "style='background-color: green;'";

                    //collect the last 3
                    if($downs_index <= 2) {
                        $downs[$downs_index] = new stdClass();
                        $downs[$downs_index]->x = $row['x_percent'];
                        $downs[$downs_index]->y = $row['y_percent'];
                        $downs[$downs_index]->timestamp = strtotime($row['date_created']);
                        $downs_index++;
                    }
                }
            }
            $output .= "><td>" . $row['ID'] . "</td><td>" . $row['x_percent'] . "</td><td>" . $row['y_percent'] . "</td><td>" . $this->type_to_english($row['type_percent']) . "</td><td>" . $row['date_created'] . "</td></tr>";
        }
        $output .= "</table>";

        $this->output = $output;

        if(count($downs) == 3) {
            //check if timestamps are close
            if(abs($downs[0]->timestamp - $downs[1]->timestamp) <= 1 && abs($downs[2]->timestamp - $downs[1]->timestamp) <= 1) {
                $this->most_recent_object = $downs;
            }
        }
    }
    //end __construct

    //print_report
    public function print_report() {
        echo $this->output;
    }
    //end print_report

    //most_recent_object
    public function most_recent_object() {
        return $this->most_recent_object;
    }
    //end most_recent_object

    //type_to_english
    private function type_to_english($type) {
        if($type == "1") return "down";
        if($type == "2") return "up";
        if($type == "3") return "move";
        return $type;
    }
//end type_to_english
}