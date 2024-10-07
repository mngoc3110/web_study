<?php
include "./view/assets/php/connectsql.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=login");
    exit();
}

$user_name = $_SESSION['username'];

// Truy vấn để lấy tất cả sản phẩm trong giỏ hàng
$sql = "SELECT course_id, course_name, teacher_id, price FROM cart_db WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->bind_result($course_id, $course_name, $teacher_id, $price);
 
$bill_items = [];
while ($stmt->fetch()) {
    $bill_items[] = [
        'course_id' => $course_id,
        'course_name' => $course_name,
        'teacher_id' => $teacher_id,
        'price' => $price,
    ];
}
$stmt->close();
   
// Chèn thông tin vào bill_db
foreach ($bill_items as $item) {
    $insert_sql = "INSERT INTO bill_db (user_id, course_id, course_name, teacher_id, price) VALUES (?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sssss", $user_name, $item['course_id'], $item['course_name'], $item['teacher_id'], $item['price']);
    $insert_stmt->execute();
    $insert_stmt->close();
}

// Xóa thông tin trong cart_db
$delete_sql = "DELETE FROM cart_db WHERE user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("s", $user_name);
$delete_stmt->execute();
$delete_stmt->close();

// Lấy mã giảm giá từ URL
$discountCode = isset($_GET['discountCode']) ? $_GET['discountCode'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="./view/assets/css/checkout.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>PAY NOW</h1>
    <form id="payment-form" action="./view/assets/php/process_payment.php" method="POST">
        <input type="hidden" name="discountCode" value="<?php echo htmlspecialchars($discountCode); ?>">
        <div id="card-element"></div>
        <label for="amount">Price ($):</label>
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
        <input type="number" id="amount" name="amount" required>
        <button type="submit">Xác Nhận Thanh Toán</button>
        <a href="./view/assets/php/process_back.php"><button type="button">Back</button></a>
    </form>
    
    <div id="card-errors" role="alert"></div>

    <script>
        var stripe = Stripe('pk_test_51Q5qt2GlgSWRtVv1jbkb68UeXoaiMmZo4mjI0l1GOiFqGXGb0wBDEmr4IwvWz93bYN4SCz8MM21dlSAEO8zvqrAJ00cooL6mfY');
        var elements = stripe.elements();
        
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', result.token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        });
    </script>
     <script src="./view/assets/js/main.js"></script>
</body>
</html>