<?php
include 'connection.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');
}
include 'add_to_cart.php';
?>
<html>
<head>
   <title>category</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="user_css.css">
</head>
<body>
<?php 
include 'user_header.php'; 
?>

<style>
    .artist-details {
        color: white; /* White color */
        font-size: 18px;
        text-align: center; /* Align center */
    }
    .artist-details h1 {
        font-size: 24px;
        color: white; /* Matte gold color for the heading */
    }
</style>
<div class="box-container">
<?php
// Fetch artist details from the database
$artist_name = $_GET['artist_name'];
$artist_query = "SELECT * FROM `users` WHERE name = '$artist_name'";
$artist_result = mysqli_query($conn, $artist_query);
if ($artist_row = mysqli_fetch_assoc($artist_result)) {
    // Display artist details
    echo '<div class="artist-details">';
    echo '<h1 class="heading"><span>' . $artist_row['name'] . '</span> art <a href="#all"><span>&#8594;</a></span></h1>';
    echo '<p>Contact us for personal art: ' . $artist_row['contact'] . '</p>';
    echo '<p>Class per hour: Rs ' . $artist_row['class_cost'] . '</p>';
    echo '</div>';
} else {
    echo '<h1 class="heading"><span>Artist details not found</span></h1>';
}
?>
</div>
<section class="products">
    <div class="box-container">
        <?php  
        // Fetch products related to the artist
        $res = mysqli_query($conn, "SELECT * FROM `products` where artist_name= '$artist_name'") or die('query failed');
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                include 'products.php';
            }
        } else {
            echo '<p class="empty">No artworks added yet!</p>';
        }
        ?>
    </div>
</section>
<script src="js/script.js"></script>
</body>
</html>
