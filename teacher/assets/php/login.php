<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login Admin</title>
</head>
<body>

<?php
    include "connectsql.php";
    $error_message = "";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        $sql = "SELECT password FROM teacher_db WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed);
            $stmt->fetch();
    
          
            if (password_verify($password, $hashed)) {
                $_SESSION['username'] = $username;
               
                echo '<script>
                Swal.fire({
                    title: "NOTICE",
                    text: "LOGIN SUCCESSFUL",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../../index.php?page=main"; 
                });
                </script>';
              
            } else {
                
                echo "Mật khẩu không đúng!";
                echo '<script>
                Swal.fire({
                    title: "WARNING",
                    text: "LOGIN FAILD",
                    icon: "error",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../../index.php"; 
                });
                </script>';
            }
        } else {
           
            echo "Tên đăng nhập không tồn tại!";
        }
        $stmt->close();

    }  
    if (isset($conn)) {
        $conn->close();
    }
?>
</body>
</html>