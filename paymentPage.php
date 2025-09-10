<?php
include 'database.php';

if (!isset($_GET['id']))
    die("User ID missing.");

$userId = $_GET['id'];

// Handle Cancel
if (isset($_GET['cancel']) && $_GET['cancel'] == 1) {
    $stmt = $conn->prepare("UPDATE users SET payment_status=2 WHERE id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();
    header("Location: paymentHome.php");
    exit();
}

// Fetch user info
$stmt = $conn->prepare("SELECT name, amount, invoice FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Generate invoice if empty
if (empty($user['invoice'])) {
    $invoiceNumber = 'INV-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
    $stmt = $conn->prepare("UPDATE users SET invoice=? WHERE id=?");
    $stmt->bind_param("si", $invoiceNumber, $userId);
    $stmt->execute();
    $stmt->close();
    $user['invoice'] = $invoiceNumber;
}

// Handle Card Payment Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_number = $_POST['cardNumber'];
    $expMM = $_POST['expMM'];
    $expYY = $_POST['expYY'];
    $cvv = $_POST['cvv'];
    $ch_name = $_POST['chName'];
    $save_next = isset($_POST['save']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET card_number=?, mm=?, yy=?, cvv=?, ch_name=?, save_next=?, status=1 WHERE id=?");
    $stmt->bind_param("sssssii", $card_number, $expMM, $expYY, $cvv, $ch_name, $save_next, $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: paymentSuccess.php?id=$userId");
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

    <div class="bg-white rounded shadow-lg md:w-1/3 mx-auto">
        <form method="POST">
            <div class="flex justify-between items-center p-4">
                <a href="paymentHome.php" class="text-fuchsia-500 hover:text-gray-500"> ◀ </a>
                <h1 class="text-xl font-semibold text-fuchsia-500">Payment</h1>
                <a href="paymentHome.php?cancel=1" class="text-fuchsia-500 hover:text-gray-500 font-bold"> ✕ </a>
            </div>
            <div class="p-6">
                <div class="bg-gray-100 p-2 flex items-center gap-4 rounded-lg mb-6">
                    <div class="p-4 rounded-xl bg-gray-300">IMG</div>
                    <div>
                        <p class="font-bold text-black">PAYSTATION</p>
                        <p class="text-sm text-gray-500">Invoice: <?= $user['invoice'] ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mb-6 w-1/6">
                    <img class="cursor-pointer" src="images/VISA.png" alt="" id="visa">
                    <img class="cursor-pointer" src="images/master.png" alt="" id="master">
                </div>
                <button class="btn rounded w-full text-fuchsia-500 mb-4 bg-gray-100 p-4">Card Information</button>
                <div class="flex items-center space-x-2 mb-6 w-1/6">
                    <img class="cursor-pointer" src="/images/VISA.png" alt="" id="visa">
                    <img class="cursor-pointer" src="/images/master.png" alt="" id="master">
                </div>
                <div class="space-y-4">
                    <div>
                        <input type="text" name="cardNumber" placeholder="Card Number" inputmode="numeric"
                            class="w-full input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                            id="cardNumberId" oninput="focusOnNext()" required>
                    </div>
                    <div class="flex space-x-4">
                        <input type="text" placeholder="MM" name="expMM" id="mmInputId" oninput="focusOnNext()"
                            class="w-1/3 input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                            required>
                        <input type="text" placeholder="YY" name="expYY" id="yyInputId" oninput="focusOnNext()"
                            class="w-1/3 input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                            required>
                        <input type="text" placeholder="CVV" name="cvv" id="cvvInputId" oninput="focusOnNext()"
                            class="w-1/3 input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                            required>
                    </div>
                    <div>
                        <input type="text" placeholder="Card Holder Name" name="chName" id="cardHolderNameInputId"
                            class="w-full input p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-fuchsia-500"
                            required>

                    </div>
                </div>
                <div class="flex items-center justify-between mt-6 mb-6">
                    <div class="flex items-center">
                        <input id="save-card" type="checkbox" name="save" class="h-4 w-4 rounded">
                        <label for="save-card" class="ml-2 text-sm text-gray-500">Save for next payment</label>
                    </div>
                    <a href="#" class="text-sm text-purple-600 font-semibold hover:underline">Terms & Conditions</a>
                </div>
                <button type="submit" id="payButton"
                    class="w-full bg-fuchsia-700 text-white font-bold p-3 rounded-lg hover:bg-gray-300/70">
                    Pay BDT <?= $user['amount'] ?>
                </button>

                <button type="button" name="cancel"
                    onclick="window.location.href='paymentHome.php?cancel=1&id=<?= $userId ?>';"
                    class="w-full mt-2 bg-white text-gray-500 font-bold p-3 rounded-lg border border-fuchsia-500 hover:bg-gray-100">
                    Cancel </button>
            </div>


        </form>
        <div class="mt-6 px-6">
            <p class="text-xs text-black font-semibold"> By clicking Pay, you agree to our <a href="#"
                    class="text-purple-600">Terms and Conditions</a> </p>
            <div class="flex items-center justify-center space-x-1 mt-4 text-sm text-gray-500">
                <p>Powered by</p> <span class="text-fuchsia-900 font-bold">U</span> <span
                    class="font-bold text-gray-800">Pay</span>
            </div>
        </div>
    </div>

    <script>
        const cardInputField = document.getElementById('cardNumberId')
        const mmInputField = document.getElementById("mmInputId")
        const yyInputField = document.getElementById("yyInputId")
        const cvvInputField = document.getElementById("cvvInputId")
        const cardHolderNameInputField = document.getElementById('cardHolderNameInputId')

        const visaSelect = document.getElementById('visa')
        const masterSelect = document.getElementById('master')

        function focusOnNext() {
            if (cardInputField.value.length >= 16) mmInputField.focus();
            if (mmInputField.value.length >= 2) yyInputField.focus();
            if (yyInputField.value.length >= 2) cvvInputField.focus();
            if (cvvInputField.value.length >= 3) cardHolderNameInputField.focus();
        }

        visaSelect.addEventListener('click', () => {
            visaSelect.classList.add('border', 'border-fuchsia-500')
            masterSelect.classList.remove('border', 'border-fuchsia-500')
        })

        masterSelect.addEventListener('click', () => {
            visaSelect.classList.remove('border', 'border-fuchsia-500')
            masterSelect.classList.add('border', 'border-fuchsia-500')
        })
    </script>
</body>

</html>