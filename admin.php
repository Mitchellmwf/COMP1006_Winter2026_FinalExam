<?php 
    require "./includes/auth.php";
    require "./includes/connect.php";
    require "./includes/header.html";

     // Get all images (newest first)
    $sql = "SELECT * FROM image_gallery";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $images = $stmt->fetchAll();

?>

<div class="container">
    <?php if (empty($images)): ?>
        <div class="alert">
            <p>No images to show</p>
        </div>
    <?php endif; ?>
    <table>
        <thread>
            <tr>
            <th>Image ID</th>
            <th>Image Name</th>
            <th>Image</th>

            <th>Actions</th>
            </tr>
        </thread>
        <tbody>
            <!-- Loop through orders and show in table -->
            <?php foreach ($images as $image): ?>
                <tr>
                <td><?= htmlspecialchars($image['id']); ?></td>
                <td><?= htmlspecialchars($image['name']); ?></td>
                <td><img src="<?= htmlspecialchars($image['path']); ?>"/></td>
                <td>        
                    <a
                    href="./processDelete.php?image_id=<?= urlencode($image['id']); ?>"
                    onclick="return confirm('Are you sure you want to delete this order?');"
                    class="button">
                    Delete
                    </a>
                </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
            </br>
            </br>
    <a href="./index.php" class="button">Back to Gallery</a>
    <a href="./signout.php" class="button">Log Out</a>
</div>
            </br>
<div class="container" style="min-height: 100px;">
    <form method="post" enctype="multipart/form-data">
        <h1>Add image</h1>
        <input type="file" id="image" name="image" accept="image/*" required>
            </br>
        <button class="button" type="submit">upload</button>
    </form>
<?php
    $imagePath = null;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE ){
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK ){
                $errors[] = 'Error uploading image.';
            }
            else {
                //Array to hold allowed file types
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                //detect the file type of the uploaded image
                $detectedType = mime_content_type($_FILES['image']['tmp_name']);
                // check if the detected file type is in the allowed types array
                if (!in_array($detectedType, $allowedTypes, true)) {
                    $errors[] = 'Invalid image type. Allowed types: JP(E)G, PNG, WEBP.';
                }
                // Limit file size to 20MB
                elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    $errors[] = 'Image size exceeds 5MB limit.';
                }
                else {
                    // Build the file name and move it to the uploads directory
                    // get the file extension
                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    // create a unique file name so uploaded files don't overwrite each other
                    $safeFilename = uniqid('image_', true) . '.' . strtolower($extension);
                    //Build the full server path where the file will be stored
                    $destination = __DIR__ . '/uploads/' . $safeFilename;
                    //Check if the file uploaded successfully and move it to the destination
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                        // Save the relative path to the image for storing in the database
                        $imagePath = './uploads/' . $safeFilename;
                    } else {
                        $errors[] = 'Failed to move uploaded image.';
                    }
                }
            }
        }
        else{
            $errors[] = "Nothing to uplaod";
        }

        if (empty($errors)){
            // Build the SQL query 
            $sql = "INSERT INTO image_gallery (name, path) value (:name, :path)";
            // Prepare the insert statement
            $stmt = $pdo->prepare($sql);

            // Bind the values to the query parameters
            $stmt->bindParam(':name', $safeFilename);
            $stmt->bindParam(':path', $imagePath);

            // Execute the insert query
            $stmt->execute();
            header("Location: ./admin.php");
        }
        else{
            echo "<div class='alert'>";
            foreach ($errors as $error){
                echo htmlspecialchars($error);
            }
            echo "</div>";
        }
    }
?>