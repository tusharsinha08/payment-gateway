<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];

    // Prepare SQL
    $stmt = $conn->prepare("INSERT INTO users (name, mobile, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $mobile, $amount);
    $stmt->execute();

    // Get inserted user ID
    $userId = $stmt->insert_id;

    $stmt->close();
    $conn->close();

    // Redirect to payment page with user ID
    header("Location: paymentPage.php?id=$userId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-4">

    <div class="bg-white rounded shadow-lg md:w-1/3 p-6 mx-auto">
        <div class="bg-gray-100 text-center rounded-lg mb-6">
            <div class="p-6 rounded-xl text-center bg-gray-300">
                <img class="h-[100px] rounded-xl" src="images/logo.png" alt="">
            </div>
        </div>

        <form id="myInput" method="POST" class="space-y-4">
            <div>
                <label class="font-bold">Name:<span class="text-red-400 ml-2">*</span></label>
                <input type="text" name="name" placeholder="Enter your name"
                    class="w-full input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                    id="name" required>
            </div>

            <div>
                <label class="font-bold">Mobile:<span class="text-red-400 ml-2">*</span></label>
                <input type="text" name="mobile" placeholder="Enter your phone"
                    class="w-full input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                    id="mobile" required>
            </div>

            <div>
                <label class="font-bold">Amount:<span class="text-red-400 ml-2">*</span></label>
                <input type="number" name="amount" placeholder="Enter amount"
                    class="w-full input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                    id="amount" required>
            </div>

            <button type="submit" id="payButton"
                class="w-full bg-fuchsia-700 text-white font-bold p-3 rounded-lg hover:bg-fuchsia-800">
                Pay
            </button>

            <button type="button" onclick="window.location.reload()"
                class="w-full bg-red-700 text-white font-bold p-3 rounded-lg hover:bg-red-800">
                Cancel
            </button>
            <div>
                <img src="images/paystation.jpeg" alt="">
            </div>

        </form>
    </div>

    <script>
        const inputs = document.querySelectorAll(".input");
        inputs.forEach((input, index) => {
            input.addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    } else {
                        document.getElementById("payButton").click();
                    }
                }
            });
        });
    </script>
</body>

</html>