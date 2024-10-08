<?php
include 'connection.php';
session_start();
$artist_id = $_SESSION['artist_id'];
if(!isset($artist_id)){
   header('location:login.php');
}
if(isset($_POST['update_profile'])){
   $name = $_POST['name'];
   $email = $_POST['email'];
   $contact = $_POST['contact'];
   $class_cost = $_POST['class_cost'];
   
   mysqli_query($conn, "UPDATE `users` SET name = '$name', email='$email', contact='$contact', class_cost='$class_cost' WHERE id = '$artist_id'") or die('query failed');
   
   $old_pw= $_POST['old_pw'];
   $update_pw = $_POST['update_pw'];
   $new_pw = $_POST['new_pw'];
   $c_pw = $_POST['c_pw'];

   if(!empty($update_pw) AND !empty($new_pw) AND !empty($c_pw)){
      if($update_pw != $old_pw){
         $message[] = 'Old password not matched!';
      } elseif($new_pw != $c_pw){
         $message[] = 'Confirm password not matched!';
      } else{
         mysqli_query($conn, "UPDATE `users` SET password='$new_pw' WHERE id = '$artist_id'") or die('query failed');
         $message[] = 'Password updated successfully!';
      }
   }
}
?>

<html>
<head>
   <title>Update Artist Profile</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="profile.css">
   <link rel="stylesheet" href="admin_css.css">
   <link rel="stylesheet" href="artist_report.css">
</head>
<body style="background-color: black; color: white;">
<?php include 'artist_header.php'; ?>
<h1 class="title">Update <span>Profile</span></h1>
<section class="update-profile">
   <form action="" method="POST">
      <div class="flex">
         <?php
         $res = mysqli_query($conn, "SELECT * FROM `users` WHERE id='$artist_id'") or die('query failed');
         if(mysqli_num_rows($res) > 0){
            $row = mysqli_fetch_assoc($res);
         ?>
         <div class="inputBox">
            <span>Username:</span>
            <input type="text" name="name" value="<?php echo $row['name']; ?>" placeholder="Update username" required class="box">
            <span>Email:</span>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" placeholder="Update email" required class="box">
            <span>Contact:</span>
            <input type="text" name="contact" value="<?php echo $row['contact']; ?>" placeholder="Update contact" required class="box">
            <span>Class Cost:</span>
            <input type="text" name="class_cost" value="<?php echo $row['class_cost']; ?>" placeholder="Update class cost" required class="box">
         </div>
         <div class="inputBox">
            <input type="hidden" name="old_pw" value="<?php echo $row['password']; ?>">
            <span>Old Password:</span>
            <input type="password" name="update_pw" placeholder="Enter previous password" class="box">
            <span>New Password:</span>
            <input type="password" name="new_pw" placeholder="Enter new password" class="box">
            <span>Confirm Password:</span>
            <input type="password" name="c_pw" placeholder="Confirm new password" class="box">
         </div>
      </div>
      <input type="submit" class="btnn" value="Update Profile" name="update_profile">
   </form>
   <?php
   }
   ?>
</section>
<script src="src.js"></script>
</body>
</html>
