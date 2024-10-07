<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "./view/assets/php/connectsql.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=login"); // Redirect nếu chưa đăng nhập
    exit();
}

$user_name = $_SESSION['username'];

// Truy vấn để lấy tất cả sản phẩm trong giỏ hàng
$sql = "SELECT course_id, course_name, teacher_id, price FROM cart_db WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->bind_result($course_id, $course_name, $teacher_id, $price);
$total_price = 0;
$cart_items = [];
while ($stmt->fetch()) {
    $cart_items[] = [
        'course_id' => htmlspecialchars($course_id),
        'course_name' => htmlspecialchars($course_name),
        'teacher_id' => htmlspecialchars($teacher_id),
        'price' => htmlspecialchars($price),
    ];
    
    // Loại bỏ ký hiệu đô la và chuyển đổi giá trị sang float
    $price_value = floatval(str_replace(['$', ','], '', $price)); // Loại bỏ $ và dấu phẩy

    if (is_numeric($price_value)) {
        $total_price += $price_value; // Cộng giá trị vào tổng
    } else {
        error_log("Invalid value: " . $price);
    }
}
$stmt->close();

// Mã giảm giá
$discountedPrice = $total_price; // Khởi tạo giá trị giảm giá bằng tổng giá trị ban đầu
$discountCode = isset($_POST['discountCode']) ? $_POST['discountCode'] : '';
$discountMessage = ""; // Khởi tạo biến thông báo giảm giá

// Kiểm tra mã giảm giá
if ($discountCode === "GAUMAUHONG") {
    $discountedPrice *= 0.5; // Giảm 50%
    $discountMessage = "Giảm giá 50% đã được áp dụng!"; // Thông báo giảm giá
} elseif ($discountCode !== '') {
    $discountMessage = "Mã giảm giá không hợp lệ!"; // Thông báo mã không hợp lệ
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/cart.css">
    <title>Giỏ Hàng của Tôi</title>
    <style>
        .discount-container {
            margin: 20px 0;
        }

        #discountCode {
            padding: 10px;
            width: 200px;
        }

        #applyDiscount {
            padding: 10px;
        }
    </style>
</head>
<body>
    <main>
        <h1>PAY NOW</h1>
        <form method="POST" id="discountForm">
            <div class="discount-container">
                <input type="text" name="discountCode" id="discountCode" placeholder="Nhập mã giảm giá" />
                <button type="submit" id="applyDiscount">Apply</button>
                <p id="discountMessage"><?php echo $discountMessage; ?></p> <!-- Hiển thị thông báo -->
            </div>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Course id</th>
                    <th>Course name</th>
                    <th>Teacher name</th>
                    <th>Price</th>
                    <th>Method</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cart_items)): ?>
                    <tr>
                        <td colspan="5">Your shopping cart is empty.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo $item['course_id']; ?></td>
                            <td><?php echo $item['course_name']; ?></td>
                            <td><?php echo $item['teacher_id']; ?></td>
                            <td><?php echo $item['price']; ?></td>
                            <td>
                                <form action="./view/assets/php/delete_item.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="course_id" value="<?php echo $item['course_id']; ?>">
                                    <button class="delete-button" type="submit" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                        <td id="totalPrice"><strong><?php echo number_format($discountedPrice, 2, '.', ''); ?> $</strong></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="index.php?page=checkout&discountCode=<?php echo urlencode($discountCode); ?>"><button class="checkout">Pay</button></a><!-- Nút thanh toán -->
    </main>

    <script>
        document.getElementById('applyDiscount').addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn chặn form gửi ngay lập tức
            const discountCode = document.getElementById('discountCode').value;
            const discountMessage = document.getElementById('discountMessage');

            // Gửi mã giảm giá qua form
            document.getElementById('discountForm').submit();
        });
    </script>
     <script src="./view/assets/js/main.js"></script>
</body>
</html>