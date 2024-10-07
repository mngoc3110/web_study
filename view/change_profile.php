<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "./view/assets/php/connectsql.php";
$username = isset($_GET['username']) ? $_GET['username'] : '';
if ($username) {
    $sql = "SELECT * FROM login_db WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
        $phone = $row['phone'];
        $email = $row['email'];
        $school = $row['school']; 
        $user_id = $row['id']; 
        $avatar = $row['avatar_image'];
    } else {
        echo "Không tìm thấy người dùng.";
        exit; 
    }
    $stmt->close();
} else {
    echo "Username không hợp lệ.";
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật Thông tin Cá nhân</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .profile-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .left-side-container {
            width: 30%;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .right-side-container {
            width: 70%;
            padding: 20px;
        }
        img {
            width: 100%;
            border-radius: 50%;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="left-side-container">
        <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Hình ảnh người dùng">
            <div class="info-left-container">
              
                <!-- Có thể thêm các thông tin khác nếu cần -->
            </div>
        </div>
        <div class="right-side-container">
            <h2>UPDATE INFO</h2>
            <form action="./view/assets/php/change_profile.php" method="POST">
                
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" >
                <label for="fullname">Full name:</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" >
                <label for="phone">Phone:</label>
                <input type="number" id="phone" name="phone" value="<?php echo $phone; ?>">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" >
                <label for="email">School:</label>
                <input type="text" id="school" name="school" value="<?php echo $school; ?>" >

                <h3>CHANGE PASSWORD?</h3>
                <label>Current password</label>
                <input type="text" name="currentpassword">
                <label>New password</label>
                <input type="text" name="newpassword">
                <label>Repeat password</label>
                <input type="text" name="repeatpassword">

                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> <!-- ID của người dùng -->
                <button type="submit">submit</button>
               
                <button ><a href="index.php?page=home" style="color:white">Back</a></button>
            </form>
        </div>
    </div>
    <script src="./view/assets/js/main.js"></script>
</body>
</html>