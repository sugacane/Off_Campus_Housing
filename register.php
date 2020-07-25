<?php
// Include config file
require_once 'backend/config/off_campus_db_connect.php';

// Define variables and initialize with empty values
$fnameErr = $lnameErr = $genderErr = $emailErr = $telErr = $passwdErr = $confirmpasswrdErr = "";
$fname = $lname = $gender = $email = $tel = $passwd = $confirmpasswrd = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $fnameQuery = "SELECT Lanlord_ID FROM landlord_info WHERE  = :username";
        $lnameQuery = "SELECT id FROM users WHERE username = :username";
        $genderQuery = "SELECT id FROM users WHERE username = :username";
        $emailQuery = "SELECT id FROM users WHERE username = :username";
        $telQuery = "SELECT id FROM users WHERE username = :username";
        $passwdQuery = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }


    // First name
    if (empty($_POST["fname"]))
    {
        $fnameErr = " FirstName is required";
    }
    else
    {
        $fname = validate_input($_POST["fname"]);
    }

    // Last name
    if (empty($_POST["lname"]))
    {
        $lnameErr = "Last Name is required";
    }
    else
    {
        $lname = validate_input($_POST["lname"]);
    }

    // Gender
    if (empty($_POST["gender"]))
    {
        $genderErr = "Gender is required";
    }
    else
    {
        $gender = validate_input($_POST["gender"]);
    }

    //Email
    if (empty($_POST["email"]))
    {
        $emailErr = "Email is required";
    }
    else
    {
        $email = validate_input($_POST["email"]);

        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $emailErr = "Invalid email format";
        }
    }


    // Telephone Number
    if (empty($_POST["tel"]))
    {
        $tel = "Telephone Number is required";
    }
    else
    {
        $tel = validate_input($_POST["tel"]);
    }


    // Password
    if (empty($_POST["passwd"]))
    {
        $passwdErr = "Password is required";
    }
    else {
        $passwd = validate_input($_POST["passwd"]);
    }

}




// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}




function validate_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>