<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "./assets/php/connectsql.php";
$sql = "SELECT (SELECT COUNT(*) FROM blog_db) as blog_count, 
        (SELECT COUNT(*) FROM teacher_db) as teacher_count,
        (SELECT COUNT(*) FROM comment_db) as comment_count,
        (SELECT COUNT(*) FROM courses) as course_count,
        (SELECT COUNT(*) FROM login_db WHERE role = 2) as student_count
        ";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

//lấy thông tin admin 
$sql_info_admin = "SELECT * FROM login_db WHERE role = 0";
$result_admin =     $conn->query($sql_info_admin);
$admins = [];
while($admin = $result_admin->fetch_assoc()){
    $admins[] =  $admin;
}
// lây thông tin giáo viên
$sql_info_teacher = "SELECT * FROM teacher_db";
$reuslt_teacher = $conn->query($sql_info_teacher);
$teachers=[];
while($teacher = $reuslt_teacher->fetch_assoc()){
    $teachers[] = $teacher;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./assets/css/main.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin main content</title>
</head>
<body>
    <main>
        <!-- overview section (số lượng bài viết, khoá học)-->
        <section class="overview-section">
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="title-container">
                    <div class="main-text">Teacher</div>
                    <?php echo '<div class="main-number">'.htmlspecialchars($row['teacher_count']).'</div>';?>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="title-container">
                    <div class="main-text">Course</div>
                    <?php echo '<div class="main-number">'.htmlspecialchars($row['course_count']).'</div>';?>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-brands fa-blogger-b"></i>
                </div>
                <div class="title-container">
                    <div class="main-text">Blog</div>
                    <?php echo '<div class="main-number">'.htmlspecialchars($row['blog_count']).'</div>';?>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-comment"></i>
                </div>
                <div class="title-container">
                    <div class="main-text">Comment</div>
                    <?php echo '<div class="main-number">'.htmlspecialchars($row['comment_count']).'</div>';?>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-tag"></i>
                </div>
                <div class="title-container">
                    <div class="main-text">Coupon</div>
                    <div class="main-number">{number}</div>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-cart-shopping"></i>   
                </div>
                <div class="title-container">
                    <div class="main-text">Student</div>
                    <?php echo '<div class="main-number">'.htmlspecialchars($row['student_count']).'</div>';?>
                </div>
            </div>
            <div class="overview-container">
                <div class="logo-container">
                    <i class="fa-solid fa-cart-shopping"></i>   
                </div>
                <div class="title-container">
                    <div class="main-text">Order</div>
                    <div class="main-number">{number}</div>
                </div>
            </div>
        </section>
        <!-- main content -->
        <div class="line"></div>
         <section class="main-content-section">
            <div class="main-content-container">
                <!-- chia làm 3 phần bên trái là admin info -->
                <div class="admin-info-container">
                    <div class="admin-info-box">
                        <div class="logo-container">
                        <?php echo '<img src="'.htmlspecialchars($admins[0]['avatar_image']).'" alt="Admin Avatar">'; ?>
                        </div>
                        <div class="admin-name">
                            <?php echo '<p>'.htmlspecialchars($admins[0]['fullname']).'</p>'?>
                        </div>
                    </div>
                    <div class="admin-info-text-container">
                        <div class="admin-row">
                            <p class="admin-row-main-text">Full name:</p>
                            <?php echo '<p>'.htmlspecialchars($admins[0]['fullname']).'</p>'?>
                        </div>
                        <div class="admin-row">
                            <p class="admin-row-main-text">NickName:</p>
                            <?php echo '<p>'.htmlspecialchars($admins[0]['nickname']).'</p>'?>
                        </div>
                        <div class="admin-row">
                            <p class="admin-row-main-text">Phone:</p>
                            <?php echo '<p>'.htmlspecialchars($admins[0]['phone']).'</p>'?>
                        </div>
                        <div class="admin-row">
                            <p class="admin-row-main-text">role:</p>
                            <p>Admin</p>
                        </div>
                    </div>
                </div>
                <!-- thông tin chung của toàn bộ giáo viên có overflow y-->
                <div class="teacher-info-container">
                    <div class="teacher-main-text">
                        <div class="line-decor"></div>
                            <p class="teacherlist">Teacher list</p>
                        <div class="line-decor"></div>
                    </div>
                    <div class="teacher-info-container-box">

                        <?php
                            foreach($teachers as $t){
                                echo "<div class='teacher-info-box'>";
                                echo "  <div class='teacher-image-box'";
                                echo '<img src="'.htmlspecialchars($t['avatar']).'" alt="teacher Avatar">';
                                echo "</div>";
                                echo "<div class='teacher-info-main-text'>";
                                echo '  <p>'.htmlspecialchars($t['fullname']).'</p>';
                                echo '  <p>'.htmlspecialchars($t['category']).'</p>';
                                echo "</div></div>";
                            }
                        ?>

                        
                    </div>
                </div>
                <!-- số lượng người truy cập vào web gg anaylist -->
                <div class="graph-info-container">
                    <div class="graph" id="leftgraph">
                        <div class="graph-main-text">
                            <div class="line-decor"></div>
                                <p class="graphlist">Visitors</p>
                            <div class="line-decor"></div>
                        </div>
                        <canvas id="lineChart"></canvas>
                    </div>
                    <div class="linegraph"></div>
                    <div class="graph" id="rightgraph">
                        <div class="graph-main-text">
                            <div class="line-decor"></div>
                                <p class="graphlist">Stay long</p>
                            <div class="line-decor"></div>
                        </div>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="./assets/js/main.js"></script>
</body>
</html>