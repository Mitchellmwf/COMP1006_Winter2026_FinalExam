<?php 
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }
    require_once "./includes/connect.php";
    require_once "./includes/header.html";

    // Get all orders (newest first)
    $sql = "SELECT * FROM image_gallery;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $images = $stmt->fetchAll();

    //close connection
    $pdo = null;
?>
<div class="container">
    <div id="heading">
        <h1>Image Gallery</h1> 
        <?php if (isset($_SESSION['username'])): ?>
            <a class="button" href="./admin.php">Admin</a>
        <?php else: ?>
            <a class="button" href="./login.php">Log In</a>
        <?php endif; ?>
    </div>
    <div class="gallery">
    <?php if (empty($images)): ?>
        <p>No images to show</p>
    <?php else: ?>
        <?php foreach($images as $image): ?>
            <img
                src="<?= htmlspecialchars($image['path']); ?>"
                alt="<?= htmlspecialchars($image['name']); ?>"
                style="max-width: 15vw;">
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>