<?php
    // Removes login cookies. Sets the time to the past which automatically expires the cookie.
    setcookie("loggedIn", "", time()-3600);
    setcookie("privilege", "", time()-3600);
    setcookie("username", "", time()-3600);

    // Redirects to custome phpBB logout script.
    header("Location: ../index.php");
?>