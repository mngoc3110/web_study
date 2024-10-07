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
    session_start();
    if(isset($_SESSION['username']) && $_SESSION['username'] != NULL){
        unset($_SESSION['username']);
        echo '<script>
        Swal.fire({
            title: "NOTICE",
            text: "LOGOUT SUCCESSFUL",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "../../../index.php"; 
        });
        </script>';
    }
?>
</body>
</html>