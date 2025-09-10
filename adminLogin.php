<?php
session_start();
require_once "admin.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $admin = new Admin();
    $adminData = $admin->login($username, $password);

    if ($adminData) {
        $_SESSION['admin_id'] = $adminData['id'];
        $_SESSION['admin_username'] = $adminData['username'];
        header("Location: adminDashboard.php"); // redirect to dashboard
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center text-fuchsia-700">Admin Login</h2>

        <?php if($error): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $error ?></div>
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
            <button type="submit"
                class="w-full bg-fuchsia-700 text-white font-bold p-3 rounded hover:bg-fuchsia-800">Login</button>
        </form>
        <p class="mt-4 text-sm text-gray-500 text-center">
            Not registered? <a href="adminRegister.php" class="text-purple-700 underline">Register</a>
        </p>
    </div>
</body>
</html>
