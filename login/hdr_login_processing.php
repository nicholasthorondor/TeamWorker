<?php
    // Needed for phpBB functions to work.
    define('IN_PHPBB', true);
    // Needed for phpBB functions to work.
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    // Path to root of forum.
    $phpbb_root_path = '../phpBB3/';
    // Contains phpBB required libraries.
    require_once("../phpBB3/common.php");
    require_once("../phpBB3/includes/functions_user.php");

    // Gets the username and privilege of the user that was stored in a cookie during login.
    $username = $request->variable("username", "", false,\phpbb\request\request_interface::COOKIE);
    $privilege = $request->variable("privilege", "", false,\phpbb\request\request_interface::COOKIE);

    // Redirects the user to the TeamWorker index page if there is no username cookie set.
    // This would mean the user has not successfully logged in.
    if (!$username) {
        header("Location: ../index.php");
    }

    // Gets the destination of the user from the GET data.
    $destination = $request->variable("destination", "");

    /*
    ** Gets the phpBB user id of the username specified.
    */
    function GetUserID($username) {
        $user_ids = array();
        $usernames = array($username);
        user_get_id_name($user_ids, $usernames);
        return $user_ids[0];
    }

    /*
    ** Logs the user into the phpBB system by inputting their user id.
    ** The user will have already verified themselves at the Teamworker login, this just sets up the phpBB session bypassing a double login.
    */
    function phpbbLogin($id) {

        global $phpbb_root_path, $phpEx, $user, $destination, $privilege;

        // Start phpBB session.
        $user->session_begin();
        // Create phpBB session for user id specified.
        $user->session_create($id);

        // Redirection logic according to the destination that was carried across from the TeamWoker login form.
        if($destination == "HDR" && $privilege == "HDR") {
            // Directs to the index page of the forum.
            header("Location: ../phpBB3/");
        } else if (($destination == "HDR" && $privilege == "Admin") || ($destination == "CSC3600" && $privilege == "Admin") || ($destination == "CSC8600" && $privilege == "Admin")) {
            // Directs to the supervisor menu admin page.
            header("Location: supervisor_menu.php");
        } else {
            // Directs user to the TeamWoker index page if there is no destination present in the GET data.
            header("Location: ../index.php");
        }
    }

    // Calls the phpBB login function, logs the user into the system and redirects to the appropriate destination page.
    phpbbLogin(GetUserID($username));
?>