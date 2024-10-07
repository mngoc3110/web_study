<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register</title>
</head>
<body>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51Q5qt2GlgSWRtVv1fItbQEJz3hfaxovpOQlxl6iNfgsVSlWtr38ljJvjnoq80f50WrKTSjKSX4lssIW14ej2xF2Y00e06coKeI');

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php?page=login");
    exit();
}

$user_name = $_SESSION['username'];
$course_id = $_POST['course_id']; // Lấy course_id từ form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $amount = $_POST['amount']; // Lấy số tiền từ form

    // Kết nối cơ sở dữ liệu
    include "connectsql.php";

    // Lấy giá từ course_db dựa trên course_id
    $course_query = $conn->prepare("SELECT cost FROM course_db WHERE id = ?");
    $course_query->bind_param("i", $course_id);
    $course_query->execute();
    $course_result = $course_query->get_result();

    if ($course_result->num_rows > 0) {
        $course_row = $course_result->fetch_assoc();
        $course_price = $course_row['cost'];

        // Kiểm tra mã giảm giá (nếu có)
        $discountCode = isset($_POST['discountCode']) ? $_POST['discountCode'] : '';
        $discountAmount = 0;
        $course_price = floatval(str_replace(['$', ','], '', $course_price));
        
        // Kiểm tra mã giảm giá hợp lệ
        if ($discountCode === "GAUMAUHONG") {
            $course_price *= 0.5; // Giảm 50%
        }

        // Tính số tiền thanh toán sau giảm giá
        if ($amount < $course_price) {
            // Thanh toán không thành công
            $delete_bill_sql = "DELETE FROM bill_db WHERE user_id = ?";
            $delete_stmt = $conn->prepare($delete_bill_sql);
            $delete_stmt->bind_param("s", $user_name);
            $delete_stmt->execute();
            $delete_stmt->close();

            echo "<script>
                Swal.fire({
                    title: 'Thanh toán không thành công!',
                    text: 'Số tiền nhập vào thấp hơn giá khóa học.',
                    icon: 'error',
                    confirmButtonText: 'Quay lại'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            </script>";
        } else {
            // Chuyển đổi số tiền thành cents
            $amountInCents = $course_price * 100;

            try {
                // Tạo một giao dịch
                $charge = \Stripe\Charge::create([
                    'amount' => $amountInCents,
                    'currency' => 'usd',
                    'description' => 'Thanh toán thử nghiệm',
                    'source' => $token,
                ]);

                // Ghi thông tin vào enroll_db
                $stmt = $conn->prepare("INSERT INTO enroll_db(course_id, student_name) VALUES (?, ?)");
                $stmt->bind_param("is", $course_id, $user_name);
                $stmt->execute();

                // Khởi tạo giá trị cho $new_code
                $new_code = $charge->id;

                // Chèn thông tin vào bill_db
                $update_sql = "UPDATE bill_db SET code = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $new_code, $user_name);
                $update_stmt->execute();

                // Kiểm tra xem có bản ghi nào bị ảnh hưởng không
                if ($update_stmt->affected_rows > 0) {
                    $change = $amount - $course_price; // Tính tiền thừa nếu có
                    $success_message = "Thanh toán thành công! Giao dịch ID: " . $charge->id;
                    if ($change > 0) {
                        $success_message .= " Tiền thừa: " . number_format($change, 2, ',', '.') . " $";
                    }

                    echo "<script>
                        Swal.fire({
                            title: 'Thành công!',
                            text: '$success_message',
                            icon: 'success',
                            confirmButtonText: 'Quay về trang chính'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../../../index.php?page=home';
                            }
                        });
                    </script>";
                } else {
                    echo "Không có bản ghi nào để cập nhật.";
                }

                $update_stmt->close();
            } catch (\Stripe\Exception\CardException $e) {
                // Nếu có lỗi xảy ra trong quá trình thanh toán
                $delete_bill_sql = "DELETE FROM bill_db WHERE user_id = ?";
                $delete_stmt = $conn->prepare($delete_bill_sql);
                $delete_stmt->bind_param("s", $user_name);
                $delete_stmt->execute();
                $delete_stmt->close();

                echo "Có lỗi xảy ra trong quá trình thanh toán: " . $e->getMessage();
            }
        }
    } else {
        echo "Khóa học không tồn tại.";
    }
    $course_query->close();
    $conn->close();
}
?>