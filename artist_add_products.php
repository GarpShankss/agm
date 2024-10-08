<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['artist_id'])) {
   header('location:login.php');
   exit(); // Exit to prevent further execution
}

$artist_id = $_SESSION['artist_id'];

if(isset($_POST['add_artwork'])) {
    $name =  mysqli_real_escape_string($conn, $_POST['name']);
    $artist_name = mysqli_real_escape_string($conn, $_POST['artist_name']);
    $type = mysqli_real_escape_string($conn, $_POST['arttype']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $theme = mysqli_real_escape_string($conn, $_POST['theme']);
    $len = mysqli_real_escape_string($conn, $_POST['len']);
    $bre = mysqli_real_escape_string($conn, $_POST['bre']);
    $des= mysqli_real_escape_string($conn, $_POST['des']);
    $image = $_FILES['image']['name'];
    $date = date('Y-m-d');
    $i = 100;
    $total = $price - $i;

    // Check if the artwork already exists
    $art_name_check_query = "SELECT * FROM `products` WHERE name = '$name' AND user_id = '$artist_id'";
    $art_name_check_result = mysqli_query($conn, $art_name_check_query);
    if(mysqli_num_rows($art_name_check_result) > 0) {
        $message[] = 'Artwork already added';
    } else {
        // Insert artwork into products table
        $insert_query = "INSERT INTO `products` (user_id, name, artist_name, art_type, price, image, theme, length, breadth, description, placed_on)
                         VALUES ('$artist_id', '$name', '$artist_name', '$type', '$price', '$image', '$theme', '$len', '$bre', '$des', '$date')";
        if(mysqli_query($conn, $insert_query)) {
            // Upload image file
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $message[] = 'Artwork added successfully!';
        } else {
            $message[] = 'Failed to add artwork';
        }
    }

    // Check if artist payment record already exists
    $artist_payment_check_query = "SELECT * FROM `artist_payment` WHERE name = '$name'";
    $artist_payment_check_result = mysqli_query($conn, $artist_payment_check_query);
    if(mysqli_num_rows($artist_payment_check_result) == 0) {
        // Insert artist payment record
        $insert_payment_query = "INSERT INTO `artist_payment` (user_id, name, final_price, placed_on)
                                 VALUES ('$artist_id', '$name', '$total', '$date')";
        mysqli_query($conn, $insert_payment_query);
    }
}
?>

<html>
<head>
   <title>Adding Artist Artwork</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="admin_css.css">
</head>
<body style="background-color: black">
<?php 
include 'artist_header.php'; 
?>
<section class="add-products">
   <h1 class="title">Artworks</h1>
   <?php
   if(isset($message)) {
       foreach($message as $msg) {
           echo '<p>' . $msg . '</p>';
       }
   }
   ?>
   <form action="artist_add_products.php" method="post" enctype="multipart/form-data">
      <h3>Add Artworks</h3>
      <input type="text" name="name" class="box" placeholder="Enter art name" required>
      <input type="text" name="artist_name" class="box" placeholder="Enter artist name" required>
        <select name="arttype" class="box" required>
            <option value="" selected disabled>Select art type</option>
            <option value="watercoloring">Watercoloring</option>
            <option value="painting">Painting</option>
            <option value="pencilsketching">Pencil Sketching</option>
            <option value="oilpainting">Oil Painting</option>
        </select>
        <select name="theme" class="box" required>
            <option value="" selected disabled>Select art theme</option>
            <option value="flower">Flower</option>
            <option value="city">City</option>
            <option value="animal">Animal</option>
            <option value="sea">Sea</option>
        </select>
      <input type="number" min="0" name="len" class="box" placeholder="Enter length" required>
      <input type="number" min="0" name="bre" class="box" placeholder="Enter breadth" required>
      <input type="number" min="0" name="price" class="box" placeholder="Enter price" required>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="text" name="des" class="box" placeholder="Enter description" required>
      <input type="submit" value="Add Artwork" name="add_artwork" class="btn">
   </form>
</section>
<script src="src.js"></script>
</body>
</html>
