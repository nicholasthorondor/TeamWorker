<?php

    class userauthentication {

        //.............................
        // METHODS.
        //.............................
        
        /*
        ** Functions to check to see if user has a valid login session and has the privilege level to access the page.
        ** If not the user will be redirected to the login page, along with appropriate privilege level data.
        ** Prevents users from entering restricted pages via direct url manipulation.
        */

        // Use for Dion's DB maintenance page, and any other admin only page.
        function AuthenticateDBMaintenance () {
            if (!isset($_COOKIE["loggedIn"]) && $_COOKIE["privilege"] != "Admin") {
                header("Location: ../index.php");
            }
        }

        // Use for Shina's team registration page.
        function AuthenticateTeamRegistration () {
            if (!isset($_COOKIE["loggedIn"]) && ($_COOKIE["privilege"] != "CSC3600" || $_COOKIE["privilege"] != "CSC8600")) {
                header("Location: ../index.php");
            }
        }
    }

?>