<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    error_reporting(E_ERROR | E_PARSE);
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE username = '$username'";

        $query = mysqli_query($db, $sql);

        $num_data = mysqli_num_rows($query);
        
        if($num_data == 1) {
            $admin = mysqli_fetch_array($query);
            if($password == $admin['password']) {
                // Password is correct, so start a new session
                session_start();
                            
                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $admin['id'];
                $_SESSION["username"] = $username;                            
                
                // Redirect user to welcome page
                header("location: index.php");
            } else{
                // Display an error message if password is not valid
                $password_err = "The password you entered was not valid.";
            }
        }
        
    }
    
    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>Document</title>
    <style type="text/css">
        html,body {
            height: 100%;
            background-image: url("./Assets/rektorat.png");
            background-position: center;
  			background-repeat: no-repeat;
  			background-size: cover;
            
        }
        .mid-center { 
            top: 50%; 
            left: 50%; 
            transform: translateX(-50%) translateY(-50%); 
        }
        .image {
            margin-bottom: 34px;
        }
        .content {
            background-color: rgb(75, 92, 151);
            padding-left: 32px;
            padding-right: 40px;
            border-radius: 15px;
            padding-bottom: 20px
        }
        label {
            color: rgb(241, 196, 15);
        }
        .btn {
            margin-top: 10px;
            background-color: rgb(241, 196, 15);
            color: rgb(0,55,126);
        }
    </style>
</head>
<body>
    <div class="position-relative h-100">	
		<div class="position-absolute mid-center">
            <div class="content">
                <div class="image">
                    <img src="./Assets/its_logo.png" style="margin-right: 26px;">
                    <img src="./Assets/single-sign-in_logo.png" style="margin-left: 26px;">
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>ID</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>    
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Sign in">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
