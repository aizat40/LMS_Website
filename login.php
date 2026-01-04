<?php
include 'config.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user1 WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];

            if ($_SESSION['role'] === 'lecturer') {
                header("Location: admin/index.php?status=authorized");
                exit();
            }
            else { 
            header("Location: student/index.php?status=authorized");
            exit();
            }
        } else {
          header("Location: login.html?status=invalid");
            exit();
        }
    } else {
        header("Location: login.html?status=invalid");
        exit();
    }
    $stmt->close();
    $conn->close();
?>