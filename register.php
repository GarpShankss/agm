<?php
session_start();
include 'connection.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];
    $user_type = $_POST['user_type'];
    $date=date('Y-m-d');
    
    $message = array(); // Initialize an array to store error messages
    
    // Check if the user already exists
    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');
    if(mysqli_num_rows($select_users) > 0){
        $message[] = 'User already exists!';
    } else {
        if($pass != $cpass){
            $message[] = 'Confirm password does not match!';
        } else {
            // Insert user data into the database
            mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type, placed_on) VALUES('$name', '$email', '$cpass', '$user_type','$date')") or die('Query failed');
            
            // Check if the user type is 'artist' to insert additional fields
            if($user_type == 'artist'){
                $contact = $_POST['contact'];
                $class_cost = $_POST['class_cost'];
                mysqli_query($conn, "UPDATE `users` SET contact = '$contact', class_cost = '$class_cost' WHERE email = '$email'");
            }
            
            echo 'Registered successfully!';
            header('location: login.php');
        }
    }
}

?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <title>Art Gallery</title>
</head>
<body style="background-color: black; color:white;">
<?php
// Display error messages
if(isset($message)){
    foreach($message as $msg){
        echo '<div class="message"><span>'.$msg.'</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
    }
}
?>
<form action="" method="POST">
    <div class="login-box">
        <center>
            <h1>I am new!</h1>
            <div class="textbox">
                <i class="fas fa-user" style="font-size:18px;"></i><input type="text" placeholder="Enter your name" name="name" required/>
            </div>
            <div class="textbox">
                <i class="fa fa-envelope" style="font-size:18px;"></i><input type="email" placeholder="Enter your email-id " name="email" required/>
            </div>
            <div class="textbox">
                <i class="fa fa-eye"  style="font-size:18px;"></i><input type="password" name="password" placeholder="Enter your password" required/>
            </div>
            <div class="textbox">
                <i class="fa fa-eye"  style="font-size:18px;"></i><input type="password" name="cpassword"  placeholder="Confirm your password" required/>
            </div>
            <div class="textbox">
                <i class="fas fa-users" style="font-size:18px;"></i>
                <select name="user_type" id="nname" style="width: 13em; height: 2em; font-size: 15px; " onchange="showFields(this)">
                    <option value="user">User</option>
                    <option value="artist">Artist</option>
                </select>
            </div>
            <!-- Additional fields for artist -->
            <div class="textbox" id="contactField" style="display: none;">
                <i class="fa fa-phone" style="font-size:18px;"></i><input type="text" name="contact" placeholder="Enter your contact number"/>
            </div>
            <div class="textbox" id="classCostField" style="display: none;">
                <i class="fa fa-money" style="font-size:18px;"></i><input type="text" name="class_cost" placeholder="Enter your class cost"/>
            </div>
            <tr>
                <td colspan="2" align="center"><input type="submit" class="btn" name="submit" value="Create One!"></td>
            </tr>
            <br>
            <tr><h3>Already registered? <a href="login.php">Login!</a></h3></tr>
        </center>
    </div> 
</form>
<script>
    // Function to show additional fields based on user type selection
    function showFields(select){
        var userType = select.value;
        if(userType == 'artist'){
            document.getElementById('contactField').style.display = 'block';
            document.getElementById('classCostField').style.display = 'block';
        } else {
            document.getElementById('contactField').style.display = 'none';
            document.getElementById('classCostField').style.display = 'none';
        }
    }
</script>
</body>
</html>
