<?php
require "./includes/connect.php";
require "./includes/header.html";

//if the user is already logged in, redirect them to the controls page
if (isset($_SESSION['username'])) {
    header("Location: ./admin.php");
    exit;
}
$error = "";

//if the user is already logged in, redirect them to the admin page
if (isset($_SESSION['username'])) {
    header("Location: ./admin.php");
    exit;
}

// Check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //get user input
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if ($usernameOrEmail === '' || $password === '') {
        $error = "Username/email and password are required.";
    } else {
        // SQL query to find account by username or email
        $sql = "SELECT id, username, email, password
                FROM gallery_users
                WHERE username = :login OR email = :login
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $usernameOrEmail);
        $stmt->execute();

        // fetch account information
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID and store user info in session variables
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect
            header("Location: ./admin.php");
            exit;
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    }
}
?>

<main class="container">
    <h2>Login</h2>
    <!-- Error display -->
    <?php if ($error !== ""): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <!-- Login form -->
    <form method="post" id="login-form">
        <label for="username_or_email">Username or Email</label>
        <input
            type="text"
            id="username_or_email"
            name="username_or_email"
            class="form-control mb-3"
            required
        >

        <label for="password" >Password</label>
        <input
            type="password"
            id="password"
            name="password"
            required
        >
        <!-- reCAPTCHA-ified submit button -->
       <button type="submit">Login</button>
        <a href="./signup.php" class="btn btn-secondary">Create Account</a>
        <!-- reCAPTCHA scripts -->
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script>
            function onSubmit(token) {
                document.getElementById("login-form").submit();
            }
        </script>
    </form>
</main>
