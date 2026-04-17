<?php
    require "./includes/auth.php";
    require './includes/header.html';
    //Grab and ensure task_id is valid
    $imageID = $_GET['image_id'];
    if (empty($imageID) || $imageID <= 0) {
        echo "<div class='container'>
    <h1>Invalid ID!</h1>
    <p>You will be redirected to the homepage in 3 seconds.</p>
    <p>If you are not redirected, click <a href='./controls.php'>here</a>.</p>
</div>";
        header("refresh:3;url=./admin.php");
        exit;
    }

    //connect to database
    require "./includes/connect.php";

    //delete the task using a prepared statement
    $sql = "DELETE FROM image_gallery WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $imageID);
    $stmt->execute();
    //close connection
    $pdo = null;
    
?>
<div class="container">
    <h1>Deleted!</h1>
    <p>You will be redirected to the homepage in 3 seconds.</p>
    <p>If you are not redirected, click <a href='./controls.php'>here</a>.</p>
</div>
    
<?php header("refresh:3;url=./admin.php"); ?>
