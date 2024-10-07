<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/main.css">
    <title>Main teacher</title>
</head>
<body>
    
    <!-- overview section (số lượng bài viết, khoá học)-->
    <section class="overview-section">
        <div class="overview-container">
            <div class="logo-container">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="title-container">
                <div class="main-text">Student</div>
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
    </section>
</body>
</html>