<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "connectsql.php";

if (!isset($_SESSION['username'])) {
    echo '<script>alert("Bạn cần đăng nhập trước khi đăng ký."); window.location.href = "index.php";</script>';
    exit();
}

if (isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];

    // Kiểm tra chi phí khoá học
    $check_premium = $conn->prepare("SELECT cost FROM course_db WHERE id = ?");
    $check_premium->bind_param("i", $course_id);
    $check_premium->execute();
    $check_premium->store_result();
    $check_premium->bind_result($cost);
    $check_premium->fetch();

    // Kiểm tra loại người dùng
    $user_premium = $conn->prepare("SELECT type FROM login_db WHERE username = ?");
    $user_premium->bind_param("s", $_SESSION['username']);
    $user_premium->execute();
    $user_premium->store_result();
    $user_premium->bind_result($type);
    $user_premium->fetch();

    // // Kiểm tra quyền truy cập
    // if ($cost != 'FREE' && $type == 0) {
    //     echo '<script>
    //         Swal.fire({
    //             title: "Thông báo",
    //             text: "Bạn không có quyền truy cập khoá học này. Bạn có muốn thêm vào giỏ hàng không?",
    //             icon: "warning",
    //             showCancelButton: true,
    //             confirmButtonText: "Có",
    //             cancelButtonText: "Không"
    //         }).then((result) => {
    //             if (result.isConfirmed) {
                      
    //                     window.location.href = "./view/assets/php/add-to-cart.php?course_id=' . $course_id . '";
               
    //             } else {
    //                 window.location.href = "index.php?page=home";
    //             }
    //         });
    //     </script>';
    //     exit();
    // }

    // Kiểm tra nếu đã đăng ký
    $stmt = $conn->prepare("SELECT * FROM enroll_db WHERE course_id = ? AND student_name = ?");
    $stmt->bind_param("is", $course_id, $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo '<script>
                alert("Bạn đã đăng ký khóa học này rồi.");
                window.location.href = "index.php?page=display&course_id=' . $course_id . '";
              </script>';
        exit();
    } else {
         // Kiểm tra quyền truy cập
    if ($cost != 'FREE' && $type == 0) {
        echo '<script>
            Swal.fire({
                title: "Thông báo",
                text: "Bạn không có quyền truy cập khoá học này. Bạn có muốn thêm vào giỏ hàng không?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Có",
                cancelButtonText: "Không"
            }).then((result) => {
                if (result.isConfirmed) {
                      
                        window.location.href = "./view/assets/php/add-to-cart.php?course_id=' . $course_id . '";
               
                } else {
                    window.location.href = "index.php?page=home";
                }
            });
        </script>';
        exit();
    }
        // Chưa đăng ký, thực hiện đăng ký
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $conn->prepare("INSERT INTO enroll_db(course_id, student_name) VALUES (?, ?)");
            $stmt->bind_param("is", $course_id, $_SESSION['username']);

            if ($stmt->execute()) {
                echo '<script>
                        alert("Đăng ký thành công!");
                        window.location.href = "index.php?page=display&course_id=' . $course_id . '";
                      </script>';
                exit();
            } else {
                echo '<script>alert("Đã xảy ra lỗi khi đăng ký.");</script>';
            }
        } else {
            // Hiện thông báo xác nhận khi chưa đăng ký
            echo '<script>
                Swal.fire({
                    title: "Bạn có muốn đăng ký khóa học này?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Có",
                    cancelButtonText: "Không"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("enrollForm").submit();
                    } else {
                        window.location.href = "index.php";
                    }
                });
            </script>';
        }
    }
} else {
    echo '<script>alert("Khóa học không hợp lệ."); window.location.href = "index.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Enroll Course</title>
</head>
<body>
    <form id="enrollForm" method="post" action="">
        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course_id); ?>">
    </form>
</body>
</html>