<?php
session_start();
require_once "admin.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        $admin = new Admin();
        if ($admin->register($username, $password)) {
            $admin->login($username, $password);
            header("Location: adminDashboard.php");
        } else {
            $error = "Username already exists!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center text-fuchsia-700">Admin Register</h2>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-semibold mb-1">Username</label>
                <input type="text" name="username" required
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-fuchsia-500">
            </div>
            <div>
                <label class="block font-semibold mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-fuchsia-500">
            </div>
            <div>
                <label class="block font-semibold mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required
                    class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-fuchsia-500">
            </div>
            <button type="submit"
                class="w-full bg-fuchsia-700 text-white font-bold p-3 rounded hover:bg-fuchsia-800">Register</button>
        </form>
        <p class="mt-4 text-sm text-gray-500 text-center">
            Already registered? <a href="adminLogin.php" class="text-purple-700 underline">Login</a>
        </p>
    </div>
</body>
</html>
