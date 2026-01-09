<?php
include '../config.php';
include 'stripe_config.php';

$courseID = $_GET['course_id'];

// 1. Ambil data kursus dari database
$sql = "SELECT title, price FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseID);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

// 2. Sediakan data untuk Stripe API (Harga perlu didarab 100 untuk sen)
$course_price_cents = $course['price'] * 100;

// 3. Panggil API Stripe menggunakan cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

$params = [
    'payment_method_types[]' => 'card',
    'line_items[0][price_data][currency]' => 'myr',
    'line_items[0][price_data][product_data][name]' => $course['title'],
    'line_items[0][price_data][unit_amount]' => $course_price_cents,
    'line_items[0][quantity]' => 1,
    'mode' => 'payment',
    'success_url' => $base_url . "payment_success.php?course_id=" . $courseID,
    'cancel_url' => $base_url . "courses.php",
    'customer_email' => $_SESSION['username'] . "@student.uthm.edu.my", // Contoh emel dinamik
];

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_USERPWD, $stripe_details['secret_key'] . ':');

$result = curl_exec($ch);
$session = json_decode($result);
curl_close($ch);

// 4. Redirect ke Stripe Checkout Page
if (isset($session->url)) {
    header("Location: " . $session->url);
} else {
    echo "Ralat Stripe: " . $result;
}
?>