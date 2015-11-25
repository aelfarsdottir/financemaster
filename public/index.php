<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
    
    $rows = CS50::query("SELECT symbol, shares FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
    $cash = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
    
    // render form
    render("portfolio.php", ["rows" => $rows, "cash" => $cash, "title" => "Portfolio"]);    
    }

?>