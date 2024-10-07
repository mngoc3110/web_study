<?php
    //bắt đầu phiên dịch xem có bài viết nào được public hay không
    include "./view/assets/php/connectsql.php";
    $sql = "SELECT id,name,content,rating,post_id,post_type, public,image FROM comment_db where public = 1";

    $result = $conn->query($sql); 
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/assets/css/about.css">
    <title>Web study</title>
</head>
<body>
    <!-- comment about group -->
    <section class="vision-container">
        <div class="vision-content">
            <!-- Lịch sử hình thành -->
            <div class="title-section">
                <div class="title-bar"></div>
                <div class="title-text">
                    <h2>WEB STUDY</h2>
                    <h3>VISION</h3>
                </div>
            </div>
            <div class="content-section">
                <div class="text-box">
                    <div class="note-box">
                        <div class="icon">
                            <!-- add more icon -->
                        </div>
                        <p>"To become a leading learning platform, connecting learners with the knowledge and skills necessary for personal and professional development, contributing to the building of a global learning community."
                        </p>
                    </div>
                </div>
                <div class="image-box">
                    <img src="./assets/image/study1.jpg" width="400" height="400">
                </div>
            </div>
            <!-- Sứ mệnh -->
            <div class="title-section"  id="sumenh">
                
                <div class="title-text" id="sumenh2">
                    <h2>WEB STUDY</h2>
                    <h3>MISSION</h3>
                </div>
                <div class="title-bar"  id="titlebar2"></div>
            </div>
            <div class="content-section" id="sumenh3"> 
                <div class="image-box">
                    <img src="./assets/image/study1.jpg" width="400" height="400">
                </div> 
                <div class="text-box"  id="textbox-reverse">
                    <div class="note-box"  id="notebox-reverse">
                        <div class="icon">
                            <!-- add more icon -->
                        </div>
                        <p>"We are committed to providing high-quality courses designed by leading experts in the field. Our website is not just a place to learn, but also a supportive community that helps learners develop their skills and achieve their personal goals. We strive to offer a flexible, accessible learning experience that meets the needs of everyone."

                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!-- comment section -->
    <section class="student-comment-section">
        <div class="title-container">
            <div class="line-decor"></div>
            <h2 class="section-title">COMMENT</h2>
            <div class="line-decor"></div>
            <!-- if havent watched any video show the course user bought and if not pay suggest some course -->
        </div>
        <div class="student-comment-section-container">
            <div class="scroll-container">
                <?php
                    if($result->num_rows>0){
                        while($row = $result->fetch_assoc()){
                            echo "<div class='student-comment-container'>
                                    <div class='student-comment-left-side-container'>
                                        <div class='student-comment-image-container'>";
                            echo "<img src='".$row['image']."'alt='image'/>";
                            echo "</div></div>";
                            echo " <div class='student-comment-right-side-container'>
                                        <div class='right-side-container'>
                                            <ul class='student-comment'>";
                            echo                "<li>Student name:". htmlspecialchars($row['name']) ."</li>";
                            
                            $rating = (int) htmlspecialchars($row['rating']);
                            $stars = '';
                            for ($i = 1; $i <= 5; $i++) {
                                $stars .= ($i <= $rating) ? '★' : '☆'; 
                            }
                            echo "<li>Star: $stars</li>";
                            echo                "<li>Comment:". htmlspecialchars($row['content']) ."</li>";
                            echo                "<li>Course:". htmlspecialchars($row['post_type']) ."</li>";
                            echo            "</ul></div></div></div>";
                        }
                    }
                ?>
               
            </div>
        </div>
        <form action="./view/assets/php/addcomment.php" method="POST" enctype="multipart/form-data">
        <div class="add-comment-container">
            <div class="input-container">
                <label>Name:</label>
                <input type="text" name="student-name">
                <label>Comment:</label>
                <input type="text" name="student-comment">
                <label>Course</label>
                <select name="course">
                    
                    <option value="course1">Mathematics</option>
                    <option value="course2">Information technology</option>
                    <option value="course3">Physics</option>
                    <option value="course4">Chemistry</option>
                    <option value="course5">English</option>
                </select>
                <label for="image">Upload Image:</label>
                <input type="file" id="file" name="file" accept="image/*" >
                <label>Rank service</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" />
                    <label for="star5" class="star">★</label>
                    <input type="radio" id="star4" name="rating" value="4" />
                    <label for="star4" class="star">★</label>
                    <input type="radio" id="star3" name="rating" value="3" />
                    <label for="star3" class="star">★</label>
                    <input type="radio" id="star2" name="rating" value="2" />
                    <label for="star2" class="star">★</label>
                    <input type="radio" id="star1" name="rating" value="1" />
                    <label for="star1" class="star">★</label>
                </div>
                
            </div>
            <div class="add-comment-button">
                <button>Add comment</button>
            </div>
        </div>
        </form>
    </section>
    <script src="./view/assets/js/main.js"></script>
</body>
</html>