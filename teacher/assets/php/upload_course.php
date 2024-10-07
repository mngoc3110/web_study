<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    include "connectsql.php";
   $name = $_POST['name'];
   $description = $_POST['description'];
   $link = $_POST['link'];
   $cost = $_POST['cost'];
   $username = $_SESSION['username'];
  
   $stmt = $conn->prepare("INSERT INTO course_db (name,description,link,teacher_id,cost) VALUES (?,?,?,?,?)");
   $stmt->bind_param("sssss",$name,$description,$link,$_SESSION['username'],$cost);
   if($stmt->execute()){
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "NOTICE",
            text: "UPLOAD SUCCESSFUL",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "../../index.php?page=course"; 
        });
    });
  </script>';
   }else {
    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "WARNING",
            text: "UPLOAD FAILED",
            icon: "failed",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "../../index.php?page=blog"; 
        });
    });
  </script>';
}
$stmt->close();
$conn->close();
?>
</body>
</html>