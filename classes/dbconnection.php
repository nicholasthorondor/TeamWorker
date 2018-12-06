<?php

    class dbconnection {
        //.............................
        // VARIABLES.
        //.............................
        // Database connection variable.
        private $dbc;

        //.............................
        // METHODS.
        //.............................

        /*
        ** Connect to the database.
        */
        public function ConnectToDB() {
            // Define database connection values.
            $DB_HOST = "localhost";
            $DB_USER = "root";
            $DB_PASSWORD = "";
            $DB_NAME = "project_nsd";

            $this->dbc = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME)
                    or die("Error connecting to the database.");
        }

        /*
        ** Close the database connection.
        */
        public function CloseDB() {
            mysqli_close($this->dbc);
        }

        /*
        ** Queries the database authorisation table for a username match and returns the result.
        */
        public function QueryUserAuthorisation($username) {
            $authenticateQuery = "SELECT * FROM authorisation WHERE authid = '$username'";
            $authenticateResult = mysqli_query($this->dbc, $authenticateQuery);
            return $authenticateResult;
        }

        /*
        ** Queries the database studentteam table for a username match and returns the result.
        */
        public function QueryUserTeam($username) {
            $teamQuery = "SELECT team FROM studentteam WHERE stuid = '$username'";
            $teamResult = mysqli_query($this->dbc, $teamQuery);
            return $teamResult;
        }

        /*
        ** Queries the database user table for a username match and returns the result.
        */
        public function QueryUser($username) {
            $userQuery = "SELECT * FROM user WHERE uid = '$username'";
            $userResult = mysqli_query($this->dbc, $userQuery);
            return $userResult;
        }

        /*
        ** Queries the database studentteam table for all team names and returns the result.
        */
        public function QueryTeamNames() {
            $teamNameQuery = "SELECT team FROM studentteam";
            $teamNameResult = mysqli_query($this->dbc, $teamNameQuery);
            return $teamNameResult;
        }

        /*
        ** Inserts a new entry into the studentteam table containinng the passed in username and team name.
        */
        public function InsertTeamData($username, $teamName) {
            $query = "INSERT INTO studentteam (stuid, team) VALUES ('$username', '$teamName')";
            if (mysqli_query($this->dbc, $query)) {
                return true;
            } else {
                return false;
            }
        }
		/*
		** Inserts a new user into the user table
		*/
		public function InsertNewUser($uid, $pwd, $lname, $fname){
			$query = "INSERT INTO user (uid, lname, fname) VALUES ('$uid', '$lname', '$fname')";
			$query2 = "INSERT INTO authourisation(authid, password, privilege) VALUES ('$uid','$pwd', 'HDR')";
            if (mysqli_query($this->dbc, $query) && mysqli_query($this->dbc, $query2)) 
			{
                return true;
            } else {
                return false;
            }
		}
        //.............................
        // GETTERS AND SETTERS.
        //.............................
        public function GetDBC() {
            return $this->dbc;
        }
    }

?>