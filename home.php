<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Payment Gateway</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 min-h-screen flex items-center justify-center font-sans">

    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-md w-full text-center transform transition duration-300 hover:scale-105">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Welcome to Payment Gateway</h1>
        <p class="text-gray-600 mb-6">Choose an option below to continue</p>

        <div class="space-y-4">
            <!-- Admin Login Button -->
            <a href="adminLogin.php" 
               class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg transition duration-300">
               🔐 Login as Admin
            </a>

            <!-- Payment Button -->
            <a href="paymentHome.php" 
               class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl shadow-lg transition duration-300">
               💳 Make a Payment
            </a>
        </div>

        <hr class="my-6 border-gray-300">

        <p class="text-sm text-gray-500">
            © <?php echo date("Y"); ?> Payment Gateway. All Rights Reserved.
        </p>
    </div>

</body>
</html>
