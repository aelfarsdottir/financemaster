<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("buy_form.php", ["title" => "Buy"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // lookup symbol and store in stock array (symbol, name, price)
        $stock = lookup($_POST["symbol"]);
        
        // if invalid or empty stock symbol
        if (empty($stock))
        {
            apologize("Invalid stock symbol.");
        }
        
        // if no shares are entered
        if (empty($_POST["shares"]))
        {
            apologize("Please specify how many shares you'd like to buy.");
        }
        
        // ensure non-negative integer value for shares
        if (preg_match("/^\d+$/", $_POST["shares"]) == false)
        {
            apologize("Please enter a whole number of shares.");
        }
        
        // check whether the user can afford it
        $cash = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        
        // if cost of shares exceeds available cash, apologize
        if (($_POST["shares"] * $stock["price"]) > $cash[0]["cash"])
        {
            apologize("You do not have enough money to buy these shares.");
        }
        
        // set the default timezone to use. source: http://php.net/manual/en/function.date.php
        date_default_timezone_set('EST');   
        $today = date("Y-m-d H:i:s a");
        
        // insert into history table before adjusting from portfolios
            CS50::query("INSERT INTO history (user_id, transaction, 
                datetime, symbol, shares, price) VALUES(?, ?, ?, ?, ?, ?)", 
                    $_SESSION["id"], "BUY", $today, 
                        strtoupper($_POST["symbol"]), $_POST["shares"], $stock["price"]);
        
        // calculate remaining cash
        $cashleft = $cash[0]["cash"] - ($stock["price"] * $_POST["shares"]);
        
        // if it doesn't already exist
        CS50::query("INSERT INTO portfolios (user_id, symbol, shares) 
            VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE 
                shares = shares + VALUES(shares)", 
                    $_SESSION["id"], strtoupper($_POST["symbol"]), 
                        $_POST["shares"]);
        
        // update user's cash by setting it to the amount they have left (to avoid subtraction)
        CS50::query("UPDATE users SET cash = ? WHERE id = ?", 
            $cashleft, $_SESSION["id"]);

        // redirect to homepage
        redirect("/");
    }
?>