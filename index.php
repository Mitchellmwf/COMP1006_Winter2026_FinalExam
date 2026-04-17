<?php 
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
        <h1>Image Gallery</h1> <a class="button" href="./login.php">Log In</a>
    </div>
    <div class="gallery">
    <?php if (empty($images)): ?>
        <p>No images to show</p>
    <?php else: ?>
        <?php foreach($images as $image): ?>
            <img
                src="<?= htmlspecialchars($product['image_path']); ?>"
                class="card-img-top"
                alt="<?= htmlspecialchars($product['name']); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>