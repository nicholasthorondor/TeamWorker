<?php

class login {
    //.............................
    // VARIABLES.
    //.............................
    // Error message variable for holding current error if present.
    private $errorMessage = "";
    private $username;
    private $password;
    private $userPrivilege;
    // Keeps track of if login is valid.
    private $validLogin = false;
    // Keeps track of destination coming from the index page.
    private $destination;

    //.............................
    // METHODS.
    //.............................

    /*
    ** Checks for valid destination.
    */
    public function CheckDestination() {
        // Checks for destination in GET or POST and applies value to variable if present.
        if (isset($_GET["destination"])) {
            $this->destination = $_GET["destination"];
        } else if (isset($_POST["DESTINATION"])) {
            $this->destination = $_POST["DESTINATION"];
        }

        // Checks for a destination value. If none is present or is an invalid destination redirects to the index page.
        // Prevents users from coming directly to the login page via url without a destination or an incorrect destination.
        // Which would prevent the login module forwarding the user to the correct area on login.
        if (!$this->destination) {
            header("Location: index.php");
        } else if ($this->destination != "CSC3600" && $this->destination != "CSC8600" && $this->destination != "HDR") {
            header("Location: ../index.php");
        }
    }

    /*
    ** If the login details are valid, redirect user to appropriate page depending on destination and privilege level.
    */
    public function RedirectUser() {
        if ($this->validLogin) {
            if ($this->destination == "CSC3600" && $this->userPrivilege == "Admin") {
               header('Location: ../login/hdr_login_processing.php?destination=' . $this->destination);
            } else if ($this->destination == "CSC8600" && $this->userPrivilege == "Admin") {
                 header('Location: ../login/hdr_login_processing.php?destination=' . $this->destination);
            } else if ($this->destination == "CSC3600" && $this->userPrivilege == "CSC3600") {
                header("Location: teamregistration.php");
            } else if ($this->destination == "CSC8600" && $this->userPrivilege == "CSC8600") {
                header("Location: teamregistration.php");
            } else if ($this->destination == "HDR" && $this->userPrivilege == "Admin") {
                 header('Location: ../login/hdr_login_processing.php?destination=' . $this->destination);
            } else if ($this->destination == "HDR" && $this->userPrivilege == "HDR") {
                 header('Location: ../login/hdr_login_processing.php?destination=' . $this->destination);
            } else if ($this->userPrivilege != $this->destination && $this->userPrivilege != "Admin") { // If the user tries to log into a restricted area.
                $this->errorMessage = "You do not have the privilege level to access this area.";
            } else {
                header("Location: ../index.php"); // Redirects to index page if destination value does not match. Prevents users changing destination value in url.
            }
        }
    }

    //.............................
    // GETTERS AND SETTERS.
    //.............................
    public function SetUsername($username) {
        $this->username = $username;
    }

    public function GetUsername() {
        return $this->username;
    }

    public function SetPassword($password) {
        $this->password = $password;
    }

    public function GetPassword() {
        return $this->password;
    }

    public function SetPrivilege($privilege) {
        $this->userPrivilege = $privilege;
    }

    public function GetPrivilege() {
        return $this->userPrivilege;
    }

    public function SetValidLogin($value) {
        $this->validLogin = $value;
    }

    public function GetValidLogin() {
        return $this->validLogin;
    }

    public function GetDestination() {
        return $this->destination;
    }

    public function GetErrorMessage() {
        return $this->errorMessage;
    }

    public function SetErrorMessage($message) {
        $this->errorMessage = $message;
    }
}

?>