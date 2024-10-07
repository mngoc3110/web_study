<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$error_message = "";
$login = isset($_SESSION['username']);
$user_name = $login ? $_SESSION['username'] : null; // Lưu tên người dùng nếu đã đăng nhập
include "./view/assets/php/connectsql.php";
$cart_result = null;
if ($login) {
    $user_name = $_SESSION['username'];
    $cart = "SELECT * FROM cart_db WHERE user_id = '$user_name'";
    $cart_result = $conn->query($cart);

     // Truy vấn để lấy thông tin người dùng
    $sql = "SELECT  avatar_image,fullname,role FROM login_db WHERE username = ?";
     $stmt = $conn->prepare($sql);
     
     if ($stmt === false) {
         die("Lỗi chuẩn bị câu lệnh: " . $conn->error);
     }
     
     $stmt->bind_param("s", $user_name);
     $stmt->execute();
     $stmt->bind_result($avater_image,$fullname,$role);
     if ($stmt->fetch()) {
        $_SESSION['avatar_image'] = $avater_image;
        $_SESSION['fullname'] =$fullname;
       
     }else{ 
        echo "not found";
    }

    
    $stmt->close();
}else{
    echo"not login";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/style.css">
    
    <title>Web Study</title>
</head>
<body>
    <header>
    
        <div class="header-section">
        <a href="javascript:void(0);" class="icon" id="menu"  onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
            <div class="header-container" id="headercontainer">

                <nav >
                    <ul id="nav" >
                        <li><a href="index.php?page=home">Home</a></li>
                        <li><a href="index.php?page=about">About</a></li>
                        <li><a href="index.php?page=course">Course</a></li>
                        <li><a href="index.php?page=blog">Blog</a></li>
                        <li><?php if(!$login): ?>
                        <div class="become-teacher-button">
                        <a href="index.php?page=becometeacher"><button>Become teacher</button></a>
                        </div></li>
                        <li><?php endif ?>
                        <?php if($login): ?>
                        <div class="become-teacher-button">
                        <a href="index.php?page=becometeacherlogin"><button>Become teacher</button></a>
                        </div></li>
                        <?php endif?>
                    </ul>
                </nav>
                        </div>
                <div class="orther-container">
               
                <div class="search-container">
                    <div class="search-box-container"></div>
                </div>
                <div class="icon-container">    
                    <ion-icon name="search-outline" id="myIcon" ></ion-icon>
                    <div id="search-overlay" class="overlay" style="display: none;">
                        <div class="search-box">
                            <input type="text" id="search-input" placeholder="Search..." ">
                            <button id="close-search" >Close</button>
                            <div id="search-results" class="search-results"></div>
                        </div>
                    </div>
                   
                    <div class="cart-container">
                        <ion-icon name="cart-outline" id="cartIcon"></ion-icon>
                        <div class="cart-box" id="cartBox" style="display: none;">
                            <h3>PAY NOW</h3>
                            <div class="cart-items">
                                <?php if($login)
                                    if($cart_result->num_rows>0){
                                        $number = 1;
                                        while($cart = $cart_result->fetch_assoc()){
                                            echo '<div class="line"></div>';
                                            echo '<div class="item-container">';
                                            echo '<p>' .htmlspecialchars($number).'</p>';
                                            echo '<p>.'.htmlspecialchars($cart['course_name']).'</p>';
                                            echo '<p>' .htmlspecialchars($cart['price']).'</p>';
                                            
                                            echo '</div>';
                                            echo '<div class="line"></div>';
                                            $number++;
                                        }
                                    }if(!$login){
                                        echo "<p>Không có đơn hàng</p>";
                                    }
                                ?>
                                <!-- Thêm sản phẩm vào đây bằng PHP -->
                            </div>
                            <button><a href="index.php?page=cart" style="color:blue">View cart</a></button>
                        </div>
                    </div>
                    <div class="icon-container">     
                        <ion-icon name="notifications-outline" id="notificationIcon" style="color:green"></ion-icon>
                        <div class="notification-popup" id="notificationPopup" style="display: none;">
                            <p>No notifications.</p>
                        </div>
                    </div>
                   
                    
                </div>
                
                <div class="info-container">
                    <ion-icon name="person-circle-outline" id="myicon" style="color:#CB0000"></ion-icon>
                    <div class="info-box-container">
                        <!-- Nếu chưa đăng nhập -->
                        <?php if (!$login): ?>
                        <div class="info-box" id="not-login" style="display:  none;">
                            <div class="button-container">
                                <a href="index.php?page=register"><button class="register" name="register">Register</button></a>
                                <a href="index.php?page=login"><button class="login" name="login">Login</button></a>
                            </div>
                        </div>
                        <?php else: ?>
                        <!-- Nếu đã đăng nhập -->
                        <div class="info-box" id="already-login" style="display: none;">
                            <div class="upper-box">
                                <div class="avatar">
                                    <?php echo '<img src="' . htmlspecialchars($_SESSION['avatar_image']) . '" alt="Student Image">';?>
                                </div>
                                <p class="student-name">Name: <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                            </div>
                            <div class="middle-box">
                                <div class="line"></div>
                                <p><a href="index.php?page=study" style="color:#160092">Study</a></p>
                                <p><a href="index.php?page=cart" style="color:#160092">Cart</a></p>
                              
                                <p><a href="index.php?page=becometeacherlogin" style="color:#160092">Become teacher</a></p>
                                <div class="line"></div>
                                <p>Notifications</p>
                                <p>Chat</p>
                                <div class="line"></div>
                                <p><a href="index.php?page=change&username=<?php echo $user_name; ?>" style="color:#160092">Account Manage</a></p>
                                <p><a href="index.php?page=history" style="color:#160092">History</a></p>
                            </div>
                            <div class="button-container">
                                <form action="./view/assets/php/logout.php" method="POST">
                                    <button type="submit">Log out</button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
                   
        </div>
    </header>
    <script src="./view/assets/js/main.js"></script>
    <script>
       function myFunction() {
    const headerContainer = document.getElementById('nav'); // Thay 'nav' bằng ID thực tế của phần tử
    // Chuyển đổi trạng thái hiển thị của header-container
    if (headerContainer.style.display === 'none' || headerContainer.style.display === '') {
        headerContainer.style.display = 'flex'; // Hiện lại
    } else {
        headerContainer.style.display = 'none'; // Ẩn đi
    }
}

// Đảm bảo rằng khi thay đổi kích thước màn hình, kiểu hiển thị sẽ được đặt lại nếu cần
window.addEventListener('resize', function() {
    const headerContainer = document.getElementById('nav');
    if (window.innerWidth > 990) { // Ví dụ: 990px là điểm ngắt cho responsive
        headerContainer.style.display = 'flex'; // Hiện lại khi không còn ở chế độ responsive
    }else if(window.innerWidth<990){
        headerContainer.style.display= 'none';
    }
});
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>