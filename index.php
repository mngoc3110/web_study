<?php
session_start();
$default_page = 'home';

// Kiểm tra xem có tham số 'page' trong URL không
if (isset($_GET['page'])) {
    $default_page = $_GET['page'];
}

// Chọn file cần include dựa trên tham số
switch ($default_page) {
    case 'about':
        $content = 'view/about.php';
        break;
    case 'course':
        $content = 'view/course.php';
        break;
    case 'blog':
        $content = 'view/blog.php';
        break;
    case 'register':
        $content = 'view/register.html';
        break;
    case 'login':
        $content = 'view/login.html';
        break;
    case 'changenickname':
        $content = 'view/changenickname.html';
        break;
    case 'becometeacher':
        $content = 'view/becometeacher.html';
        break;
    case 'becometeacherlogin':
        $content = 'view/become_teacher_login.html';
        break;
    case 'change':
        $content = 'view/change_profile.php';
        break;
    case 'enroll':
        $content = 'view/assets/php/enroll.php';
        break;
    case 'display':
        $content = 'view/assets/php/display_course.php';
        break;
    case 'cart';
        $content = 'view/cart.php';
        break;
    case 'blog-detail';
        $content = 'view/blog-detail.php';
        break;
    case 'checkout';
        $content = 'view/checkout.php';
        break;
    case 'history';
        $content = 'view/history.php';
        break;
    case 'study';
        $content = 'view/study.php';
        break;
    default:
        $content = 'view/main.php'; // Hoặc file mặc định
}

// Include các file cần thiết
if (in_array($default_page, ['login', 'register', 'changenickname', 'becometeacher', 'becometeacherlogin','change'])) {
    include $content;
} elseif ($default_page === 'blog'||$default_page ==='display'||$default_page==='cart'||$default_page==='checkout'||$default_page==='history') {
    include 'view/header.php';
    include $content;
    include 'view/footer.php';
} else {
    include 'view/header.php';
    include 'view/banner.html';
    include $content;
    include 'view/footer.php';
}
?>