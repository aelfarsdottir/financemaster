<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("sell_form.php", ["title" => "Sell"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // lookup symbol and store in stock array (symbol, name, price)
        $stock = lookup($_POST["symbol"]);
        
        // check whether user owns stock (in portfolios)
        $portstock = CS50::query("SELECT symbol, shares FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
        
        if (empty($stock))
        {
            apologize("Invalid stock symbol.");
        }
        else if (!empty($portstock[0]["symbol"]))
        {
            // find number of shares
            $numshares = $portstock[0]["shares"];
            
            // update user's cash by the number of shares they sold * price per share
            CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", 
                $stock["price"] * $numshares, $_SESSION["id"]);
            
            // set the default timezone to use. source: http://php.net/manual/en/function.date.php
            date_default_timezone_set('EST');   
            $today = date("Y-m-d H:i:s a");
            
            // insert into history table before deleting from portfolios
            CS50::query("INSERT INTO history (user_id, transaction, 
                datetime, symbol, shares, price) VALUES(?, ?, ?, ?, ?, ?)", 
                    $_SESSION["id"], "SELL", $today, 
                        strtoupper($_POST["symbol"]), $numshares, $stock["price"]);
            
            // delete from portfolios
            CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $portstock[0]["symbol"]);
            
            // redirect to homepage
            redirect("/");
        }
        // if the portfolio stock is empty, report that stock is not owned
        else
        {
            apologize("You do not own this stock.");
        }
        
    }
?>