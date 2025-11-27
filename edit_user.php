<?php
require 'db_connect.php';


// Check if ID is provided in URL
if (!isset($_GET['id'])) {
    echo "No user ID provided.";
    exit;
}

$id = $_GET['id'];

$conn = connectDB();

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE ID = ?");
    $stmt->execute([$username, $email, $password, $id]);

    echo "User updated! <a href='fetch_users.php'>View Users</a>";
}
?>

<h2>Edit User</h2>
<form method="post" action="">
    Username: <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    Password: <input type="text" name="password" value="<?php echo $user['password']; ?>" required><br>
    <input type="submit" value="Update User">
</form>
