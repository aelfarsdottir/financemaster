<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("quote_form.php", ["title" => "Quote"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // lookup symbol and store in stock array (symbol, name, price)
        $stock = lookup($_POST["symbol"]);
        
        // validate submission
        if (empty($stock))
        {
            apologize("Invalid stock symbol.");
        }
        else
        {
            // format price
            $price = number_format($stock["price"], 4, ".", ",");
            
            // print out the price
            render("quote_display.php", ["stock" => $stock, "price" => $price, "title" => "Get Quote"]);
        }
    }
?>