<?php
session_start();
require "./includes/connect.php";
require "./includes/header.html";

$error = "";

//when submitted, validate inputed data and then run sql query
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usernameOrEmail === '' || $password === '') {
        $error = "Username/email and password are required.";
    } else {
        $sql = "SELECT id, username, email, password
                FROM users
                WHERE username = :login OR email = :login
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $usernameOrEmail);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // if password is right, log in
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    }
}
?>

<main class="container">
    <h2>Login</h2>

    <?php if ($error !== ""): ?>
        <div class="alert">
            <?= htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" style="display: flex; flex-direction: column;">
        <div>
            <label for="username_or_email" >Username or Email</label>
            <input
                type="text"
                id="username_or_email"
                name="username_or_email"
                class="form-control mb-3"
                required
            >
        <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>
        <div>
            <button type="submit" class="button">Login</button>
            <a href="signup.php" class="button">Create Account</a>
        </div>
    </form>
</main>
