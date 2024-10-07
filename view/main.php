<?php
// Kết nối cơ sở dữ liệu
include "./view/assets/php/connectsql.php";
$login = isset($_SESSION['username']);
//hiển thị danh sách các khoá học
$course_list = "SELECT * FROM course_db WHERE public = 1";
$result_of_course = $conn->query($course_list);
while($row = $result_of_course->fetch_assoc()){
    $row_course[] = $row;
}
// hiển thị danh sách giáo viên
$teacher_lists = "SELECT fullname, category,avatar FROM teacher_db WHERE status = 1";
$result_of_teacher = $conn->query($teacher_lists);
while($row = $result_of_teacher->fetch_assoc()){
    $row_teacher[] = $row;
}
// Nếu người dùng đã đăng nhập
if ($login) {
    $user_name = $_SESSION['username'];
    // Truy vấn để lấy thông tin người dùng
    $sql = "SELECT username, role,phone, email,school, time, avatar_image,nickname,type,fullname FROM login_db WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Lỗi chuẩn bị câu lệnh: " . $conn->error);
    }
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $stmt->bind_result($username, $role, $phone, $email, $school, $time,$avater_image,$nickname,$type,$fullname);
    // Gọi fetch() để lấy dữ liệu
    if ($stmt->fetch()) {
        // Lưu thông tin người dùng vào session
        $_SESSION['username'] = $username;
        if($role==2){
            $_SESSION['role'] = 'Student';
        }else if($role==1){
            $_SESSION['role'] = 'Teacher';
        }else if($role==0){
            $_SESSION['role'] = 'admin';
        }
        if($type==0){
            $_SESSION['type'] = 'Non pay';
        }
        else if($type==1){
            $_SESSION['type'] = 'Premium';
        }
        $_SESSION['avatar_image'] = $avater_image;
        $_SESSION['phone'] = $phone;
        $_SESSION['email'] = $email;
        $_SESSION['nickname'] = $nickname;
        $_SESSION['fullname'] = $fullname;
        $avatarImage = $_SESSION['avatar_image'];
        $_SESSION['school'] = $school;
        $_SESSION['time'] = $time;
    } else {
        echo "Không tìm thấy người dùng.";
    }

    $stmt->close();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/main.css">
    <title>Web study</title>
</head>
<body>
    <?php
    if($result_of_course->num_rows>0){
        echo '<section class="history-video-section">';
        echo '<div class="title-container">';
        echo '<div class="line-decor"></div>';
        echo '<h2 class="section-title">COURSE LIST</h2>';
        echo '<div class="line-decor"></div>';
        echo '</div>';
        echo '<div class="history-video-container">';
        echo '<div class="scroll-container">';
        echo '<div class="history-video-box-container">';
        foreach($row_course as $c){
            echo '<a href="index.php?page=enroll&course_id=' . htmlspecialchars($c['id']) . '" class="video-link"><div class="history-video-box">';
           
            echo '<img src="' . htmlspecialchars($c['avatar']) . '" alt="Course Image">';
            echo '<div class="video-text">';
            echo '<div class="video-title">' . htmlspecialchars($c['name']) . '</div>';
            echo '<div class="video-number">Teacher: ' . htmlspecialchars($c['teacher_id']) . '</div>';
            echo '<div class="video-number">Price: ' . htmlspecialchars($c['cost']) . '</div>';
            echo '</div>';
          
            echo '</div></a>';
        }
        echo '</div>'; // Kết thúc history-video-box-container
        echo '</div>'; // Kết thúc scroll-container
        echo '</div>'; // Kết thúc history-video-container
        echo '</section>';
    } 
    ?>
    
   

    <!-- banner discount section -->
    <section class="banner-discount-section">
    <div class="banner-discount-container">
        <div class="banner-discount-text-container" id="promotionText">
           " Get promotional code. "
        </div>
        <div class="banner-discount-button-container">
            <button id="getDiscountButton">Get discount</button>
            <button id="cancelButton">Cancel</button>
        </div>
    </div>
</section>
    <!-- teacher list section -->
    <section class="teacher-list-section">
        <div class="title-container">
            <div class="line-decor"></div>
            <h2 class="section-title">TEACHER LIST</h2>
            <div class="line-decor"></div>
            <!-- if havent watched any video show the course user bought and if not pay suggest some course -->
        </div>
        <div class="teacher-list-container">
            <!-- demo box -->
            <div class="scroll-container">
            <div class="teacher-box-container">    
                <!-- box -->
                <?php
                    if($result_of_teacher->num_rows>0){
                        foreach($row_teacher as $c){
                            echo '<div class="teacher-list-box">';
                            echo    '<div class="teacher-list-image-container">';
                            echo        '<img src="'. htmlspecialchars($c['avatar']).' alt="Hình ảnh giảng viên">';
                            echo    '</div>';
                            echo '<div class="teacher-list-title-container">';
                            echo '<div class="teacher-list-title">'. htmlspecialchars($c['fullname']). '</div>';
                            echo '<div class="teacher-list-description">'. htmlspecialchars($c['category']). '</div>';
                echo '</div>';
        echo '</div>';
                        }
                    }
                ?>
                </div>
            </div>        
        </div>
    </section>
    <!-- course list section -->
    <?php
    if($result_of_course->num_rows>0){
        
        echo "<section class='course-list-section'>";
        echo "  <div class='title-container'>";
        echo "      <div class='line-decor'></div>";
        echo "      <h2 class='section-title'>COURSE LIST</h2>";
        echo "      <div class='line-decor'></div>";
        echo "   </div>";
        echo "   <div class='course-list-container'>";
       foreach($row_course as $c){
            echo "  <div class='course-list-box'>";
            echo "      <div class='course-list-title-container'>";
            echo "<a href='index.php?page=" . htmlspecialchars($c['id']) . "' class='video-link' style='color:#160092'><div class='course-list-title'>" . htmlspecialchars($c['name']) . "</div></a>";
            echo "      </div>";
            echo "  </div>";
        }
        echo "  </div>";
        echo "</section>";
    }
    ?>
    
    <!-- student info (if login successful) -->
    <?php if ($login): ?>
    <section class="student-info-section">
        <div class="student-info-container">
            <div class="student-info-left-side-container">
                <div class="student-info-image-container">
                    
                    <?php echo '<img src="' . htmlspecialchars($avatarImage) . '" alt="Student Image">';?>
                </div>
                <div class="student-info-left-side" id="addnickname">Change avatar</div>
                <!-- popup add nick name -->
                 <div id="addnicknamemodal" class="modaladdnickname">
                    <div class="modal-container">
                    <span class="close" id="closeModal">&times;</span>
                    <h2>Change avatar</h2>
                    <form action="" id="nicknameForm"  enctype="multipart/form-data">
                       
                        <label for="image">Upload Image:</label>
                        <input type="file" id="file" name="file" accept="image/*" >
                        <button type="submit">Save</button>
                    </form>
                    </div>
                 </div>
                <!-- default is have fun if not have nick name -->
            </div>
            <div class="student-info-right-side-container">
                <div class="right-side-container">
                    <ul class="student-info">
                        <li>Name: <?php echo htmlspecialchars($_SESSION['fullname']); ?></li>
                        <li>Nickname: <a href="index.php?page=changenickname" style="text-decoration: none; color: black;" 
       onmouseover="this.style.color='#CB0000';" 
       onmouseout="this.style.color='black';"><?php echo htmlspecialchars($_SESSION['nickname']); ?></a></li>
                        <li>Role: <?php echo htmlspecialchars($_SESSION['role']); ?></li>
                        <li>Phone: <?php echo htmlspecialchars($_SESSION['phone']); ?></li>
                        <li>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></li>
                        <li>School: <?php echo htmlspecialchars($_SESSION['school']); ?></li>
                        <li>Create time: <?php echo htmlspecialchars($_SESSION['time']); ?></li>
                        <li>Type: <?php echo htmlspecialchars($_SESSION['type']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <script src="./view/assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#nicknameForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: './view/assets/php/update_profile.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error: ' + textStatus + ' ' + errorThrown);
                }
            });
        });
    </script>
</body>
</html>