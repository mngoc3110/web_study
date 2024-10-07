
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Change Nickname</title>
</head>
<body>
<?php

include "connectsql.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = isset($_POST['nickname']) ? $_POST['nickname'] : null;
    $username = $_SESSION['username'];

    if (!$nickname) {
        echo "Nickname is required.";
        exit;
    }

    $sql = "UPDATE login_db SET nickname = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("ss", $nickname, $username);

    if ($stmt->execute()) {
        echo '<script>
        Swal.fire({
            title: "NOTICE",
            text: "Nickname updated successfully",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "../../../index.php"; 
        });
        </script>';
    } else {
        $error_message = "ERROR: " . $stmt->error;
        echo $error_message;
    }

    $stmt->close();
}
?>

    

</body>
</html>