<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // save history table into array of arrays, ordered by time in ascending order
        $rows = CS50::query("SELECT * FROM history WHERE user_id = ? ORDER BY datetime ASC", $_SESSION["id"]);

        // render form
        render("history_display.php", ["rows" => $rows, "title" => "History"]);    
    }

?>