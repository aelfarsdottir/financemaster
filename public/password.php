<?php

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("password.php", ["title" => "Change Password"]);
    }

    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validate submission
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["newpassword"]))
        {
            apologize("Please enter your new password.");
        }
        else if (empty($_POST["newpassconfirm"]))
        {
            apologize("You must confirm your new password.");
        }
        else if ($_POST["newpassword"] != $_POST["newpassconfirm"])
        {
            apologize("Please make sure your new passwords match.");
        }
        else
        {
           CS50::query("UPDATE users SET hash = ? WHERE id = ?", 
                password_hash($_POST["newpassword"], PASSWORD_DEFAULT), 
                    $_SESSION["id"]);
        
            // redirect to portfolio
            redirect("/index.php");
        }
    }
?>