<?php 
$title = $_POST['title'];
$content = $_POST['content'];
$subject= $_POST['course_id'];

include '../config.php';
$id= $_SESSION['user_id'];
$sql = "INSERT INTO community_posts (studentID,courseID, title, content, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $id, $subject, $title, $content);
$stmt->execute();
$stmt->close();
$conn->close();
header("Location: community.php?posting=success");
exit();
?>