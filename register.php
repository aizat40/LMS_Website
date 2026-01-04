<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username =  htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password =($_POST['password']);
    $role = htmlspecialchars($_POST['role']);

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO user1 (username, email, password, role) VALUES (?,?,?,?)";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ssss",$username,$email,$hashed_password,$role);

    if ($stmt->execute()) {
         header("Location: login.html?status=success");
         exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
    $stmt->close();
    $conn->close();
?>