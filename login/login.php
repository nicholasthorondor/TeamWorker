<?php
    //.......................................
    // CLASS INSTANTIATION AND DESTINATION CHECK.
    //.......................................

    // Class files and instantiation.
    require_once("../classes/loginClass.php");
    require_once("../classes/dbconnection.php");
    $login = new login();
    $dbconnection = new dbconnection();

    // Checks for a destination value. If none is present or is an invalid destination redirects to the index page.
    $login->CheckDestination();

    // Checks for a valid cookie variables indicating user is already logged in.
    // if present redirects user to destination.
    if (isset($_COOKIE["loggedIn"]) && isset($_COOKIE["privilege"]) && isset($_COOKIE["username"])) {
        $login->SetPrivilege($_COOKIE["privilege"]);
        $login->SetValidLogin(true);
        $login->RedirectUser();
    }

    //.......................................
    // LOGIN FORM DATA PROCESSING AND VALIDATION.
    //.......................................
    // Checks if form data has been posted.
    if (isset($_POST["login"])) {
        // Checks if username and password POST data has been set, and that they are not empty strings.
        if(isset($_POST["username"]) && isset($_POST["password"]) && (!empty($_POST["username"]) || !empty($_POST["password"]))) {
            // Connects to the database.
            $dbconnection->ConnectToDB();

            // Stores user input username and password field values in login object variables.
            // Trims white space and also escapes potentially dangerous characters for security.
            $login->SetUsername(mysqli_real_escape_string($dbconnection->GetDBC(), trim($_POST["username"])));
            $login->SetPassword(mysqli_real_escape_string($dbconnection->GetDBC(), trim($_POST["password"])));

            // Query database for user existance and store row result in variable.
            $authorisationRow = mysqli_fetch_array($dbconnection->QueryUserAuthorisation($login->GetUsername()));

            // If the authenticate user query results returns nothing, display an error message.
            if (!$authorisationRow["authid"] || strtolower($authorisationRow["authid"]) != strtolower($login->GetUsername())) {
                $login->SetErrorMessage("Incorrect username or username does not exist. If the error persists contact the course administrator.");
            } else {
                // If the passwords do not match display an error message.
                if (!password_verify($login->GetPassword(), $authorisationRow["password"])) {
                    $login->SetErrorMessage("Incorrect password.");
                } else {
                    // Query database for user team and store row result in variable.
                    $studentTeamRow = mysqli_fetch_array($dbconnection->QueryUserTeam($login->GetUsername()));
                    // If the user is trying to access CSC3600/CSC8600 areas and is in a team, prevent login and display message.
                    if ($studentTeamRow["team"] && $login->GetDestination() != "HDR") {
                        $login->SetErrorMessage("You are already part of a team, no need to register again.");
                    } else {
                        // If the login details are vaild, store the user privilege level in login object and set validLogin to true.
                        $login->SetPrivilege($authorisationRow["privilege"]);
                        $login->SetValidLogin(true);
                        // Stores login details in cookie variables for user authentication on other pages.
                        // Also used for phpBB, as it does not have methods to deal with SESSION variables.
                        setcookie("loggedIn", $login->GetValidLogin());
                        setcookie("privilege", $login->GetPrivilege());
                        setcookie("username", $login->GetUsername());
                    }
                }
            }
            // Close the database connection.
            $dbconnection->CloseDB();

        } else {
            $login->SetErrorMessage("Both username and password must be entered to login.");
        }
    }

    //.......................................
    // REDIRECTION LOGIC.
    //.......................................
    $login->RedirectUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teamworker | Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" type="text/css">
    <link rel="stylesheet" type="text/css" href="../style/login_style.css">
</head>
<body>
    <header>
        <h1 class="display-4"><?php echo $login->GetDestination(); ?> Login</h1>
    </header>

    <section>
        <div class="container">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <input type="hidden" name="DESTINATION" value="<?php echo $login->GetDestination(); ?>">
                    <label for="username">Username:</label>
                    <input type="text" name="username" required class="form-control" placeholder="ID">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" required class="form-control" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="submit" value="Login" name="login" class="btn btn-primary">
                </div>
                <div class="form-group">
                    <a href="../index.php">Back to Main Page</a>
                    <div>
                        <h6 class="error"><?php echo $login->GetErrorMessage(); ?></h6>
                    </div>
                </div>
            </form>
        </div>
    </section>

</body>
</html>