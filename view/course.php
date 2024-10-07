<?php
    include "./view/assets/php/connectsql.php";
    $sql = "SELECT * FROM course_db";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/course.css">
    <title>Web study</title>
</head>
<body>
    <!-- có bao nhiêu course sẽ hiện hết ở đây -->
    <div class="title-container">
      <div class="line-decor"></div>
        <h2 class="section-title">COURSE LIST</h2>
        <div class="line-decor"></div>
</div>
        <?php
    if($result->num_rows>0){
        echo '<section class="list-video-section video"  ';
        
       
        echo '<div class="list-video-container">';

        echo '<div class="list-video-box-container">';
        while($c = $result->fetch_assoc()){
            echo '<a href="index.php?page=enroll&course_id=' . htmlspecialchars($c['id']) . '" class="video-link"><div class="list-video-box">';
           
            echo '<img src="' . htmlspecialchars($c['avatar']) . '" alt="Course Image">';
            echo '<div class="video-text">';
            echo '<div class="video-title">' . htmlspecialchars($c['name']) . '</div>';
            echo '<div class="video-number">Teacher: ' . htmlspecialchars($c['teacher_id']) . '</div>';
            echo '<div class="video-number">Price: ' . htmlspecialchars($c['cost']) . '</div>';
            echo '</div>';
          
            echo '</div></a>';
        }
        echo '</div>'; // Kết thúc list-video-box-container
      
        echo '</div>'; // Kết thúc list-video-container
        echo '</section>';
    } 
    ?>
       
       <script src="./view/assets/js/main.js"></script>
</body>
</html>