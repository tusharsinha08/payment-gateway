<?php
session_start();
require_once "Admin.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.php");
    exit();
}

$admin = new Admin();

// Handle delete
if (isset($_GET['delete_id'])) {
    $admin->deleteUser((int) $_GET['delete_id']);
    header("Location: adminDashboard.php");
    exit();
}

// Handle logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = (int) $_POST['update_id'];
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $amount = $_POST['amount'];
    $card_number = $_POST['card_number'];
    $mm = $_POST['mm'];
    $yy = $_POST['yy'];
    $cvv = $_POST['cvv'];
    $ch_name = $_POST['ch_name'];
    $save_next = isset($_POST['save_next']) ? 1 : 0;
    $status = (int) $_POST['status'];

    $admin->updateUser($id, $name, $mobile, $amount, $card_number, $mm, $yy, $cvv, $ch_name, $save_next, $status);
    header("Location: adminDashboard.php");
    exit();
}

// Fetch all users
$users = $admin->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-fuchsia-700">Admin Dashboard</h1>
        <a href="adminDashboard.php?logout=1" class="bg-red-600 text-white p-2 rounded hover:bg-red-700">Logout</a>
    </div>

    <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-fuchsia-700 text-white">
            <tr>
                <th class="p-2">ID</th>
                <th class="p-2">Name</th>
                <th class="p-2">Mobile</th>
                <th class="p-2">Amount</th>
                <th class="p-2">Invoice</th>
                <th class="p-2">Status</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="border-b">
                    <td class="p-2"><?= $user['id'] ?></td>
                    <td class="p-2"><?= $user['name'] ?></td>
                    <td class="p-2"><?= $user['mobile'] ?></td>
                    <td class="p-2"><?= $user['amount'] ?></td>
                    <td class="p-2"><?= $user['invoice'] ?></td>
                    <td class="p-2">
                        <?php
                        if ($user['status'] == 0)
                            echo "<span class='text-yellow-600 font-bold'>Pending</span>";
                        elseif ($user['status'] == 1)
                            echo "<span class='text-green-600 font-bold'>Paid</span>";
                        elseif ($user['status'] == 2)
                            echo "<span class='text-red-600 font-bold'>Cancelled</span>";
                        ?>
                    </td>
                    <td class="p-2 space-x-2">
                        <!-- Edit Button: opens modal -->
                        <button onclick="openEditModal(<?= $user['id'] ?>)"
                            class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Edit</button>
                        <!-- Delete -->
                        <a href="?delete_id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')"
                            class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded w-full max-w-md">
            <h2 class="text-xl font-bold mb-4 text-fuchsia-700">Edit User</h2>
            <form id="editForm" method="POST" class="space-y-2">
                <input type="hidden" name="update_id" id="update_id">
                <div>
                    <label>Name</label>
                    <input type="text" readonly name="name" id="edit_name" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label>Mobile</label>
                    <input readonly type="text" name="mobile" id="edit_mobile" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label>Amount</label>
                    <input readonly type="number" name="amount" id="edit_amount" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label>Card Number</label>
                    <input readonly type="text" name="card_number" id="edit_card_number" class="w-full p-2 border rounded">
                </div>
                <div class="flex gap-2 mb-2">
                    <div class="flex-1">
                        <label>MM</label>
                        <input readonly type="text" name="mm" id="edit_mm" class="w-full p-2 border rounded" maxlength="2">
                    </div>
                    <div class="flex-1">
                        <label>YY</label>
                        <input readonly type="text" name="yy" id="edit_yy" class="w-full p-2 border rounded" maxlength="2">
                    </div>
                    <div class="flex-1">
                        <label>CVV</label>
                        <input readonly type="text" name="cvv" id="edit_cvv" class="w-full p-2 border rounded" maxlength="3">
                    </div>
                </div>

                <div>
                    <label>Card Holder</label>
                    <input readonly type="text" name="ch_name" id="edit_ch_name" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label>Save Next Payment</label>
                    <input type="checkbox" name="save_next" id="edit_save_next">
                </div>
                <div>
                    <label>Status</label>
                    <select name="status" id="edit_status" class="w-full p-2 border rounded">
                        <option value="0">Pending</option>
                        <option value="1">Paid</option>
                        <option value="2">Cancelled</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 mt-2">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="bg-fuchsia-700 text-white px-4 py-2 rounded hover:bg-fuchsia-800">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const users = <?= json_encode($users) ?>;

        function openEditModal(id) {
            const user = users.find(u => u.id == id);
            if (!user) return;
            document.getElementById('update_id').value = user.id;
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_mobile').value = user.mobile;
            document.getElementById('edit_amount').value = user.amount;
            document.getElementById('edit_card_number').value = user.card_number;
            document.getElementById('edit_mm').value = user.mm;
            document.getElementById('edit_yy').value = user.yy;
            document.getElementById('edit_cvv').value = user.cvv;
            document.getElementById('edit_ch_name').value = user.ch_name;
            document.getElementById('edit_save_next').checked = user.save_next == 1;
            document.getElementById('edit_status').value = user.status;

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>