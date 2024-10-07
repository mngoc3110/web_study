<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Become teacher</title>
</head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
    include "connectsql.php";
    if($_SERVER['REQUEST_METHOD']=='POST'){

        $username = $_POST['username'];
        $pass = $_POST['password'];
        $repeat = $_POST['repeat-password'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $mail = $_POST['email'];
        $category = $_POST['category'];



        $checkusername = "SELECT COUNT(*) FROM teacher_db where username =? ";
        $stmt = $conn->prepare($checkusername);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count>0){
            echo '<script>
            Swal.fire({
                title: "WARNING",
                text: "USERNAME EXITED",
                icon: "failed",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "../../../index.php?page=becometeacher"; 
            });
            </script>';
        }else{


        if(empty($username)||empty($pass)||empty($repeat)||empty($fullname)||empty($phone)||empty($mail)||empty($category)){
            echo "Missing something";
        } else if($pass!=$repeat){
            echo "Repeat password is different";
        } else{
            $hashed = password_hash($pass,PASSWORD_DEFAULT);
            $sql = "INSERT INTO teacher_db (username,password, phone, email,category,fullname) VALUES (?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisss",$username,$hashed,$phone,$mail,$category,$fullname);
            $role = 1;
            $sql_login = "INSERT INTO login_db (username, password,fullname, role,phone, email, school ) VALUES (?, ?,?,?,?,?,?)";
            $stmt_login = $conn->prepare($sql_login);
            $stmt_login->bind_param("sssiiss", $username, $hashed,$fullname,$role,$phone, $mail,$category);
            $stmt_login->execute();
            if ($stmt->execute()) {
                echo '<script>
                Swal.fire({
                    title: "NOTICE",
                    text: "SIGN UP SUCCESSFUL",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "../../../index.php"; 
                });
                </script>';
            } else {
                $error_message = "ERROR: " . $stmt->error;
            }
            $stmt->close();
        
        }
    }
}
    if (isset($conn)) {
        $conn->close();
    }
?>
</body>
</html>
