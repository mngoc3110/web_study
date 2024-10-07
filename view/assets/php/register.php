<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Register</title>
</head>
<body>

<?php


    include "connectsql.php";
    $error_message = "";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $repeat = $_POST['repeat-password'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $mail = $_POST['email']; 
        $school = $_POST['school'];

        //kiểm tra username có chưa
        $checkusername = "SELECT COUNT(*) FROM login_db where username =? ";
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
                window.location.href = "../../../index.php?page=register"; 
            });
            </script>';
        }else{

        // kiểm tra có rỗng hay sai gì không
        if(empty($username)||empty($password)||empty($repeat)||empty($phone)||empty($mail)||empty($school)||empty($fullname)){
            $error_message = "Something is missout!!!";
            echo $error_message;
        } else if($password!= $repeat){
            $error_message = "Repeat password is wrong!!!";
            echo $error_message;
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO login_db (username, password, phone,email,school,fullname) VALUES (?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param("ssssss", $username, $hashed, $phone, $mail,$school,$fullname);
 
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