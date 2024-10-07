<?php

include "connectsql.php"; // Ensure your database connection is included

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['course_id'])) {
        $course_id = $_POST['course_id'];
        $user_name = $_SESSION['username'];

        // Prepare and execute the delete statement
        $delete_sql = "DELETE FROM cart_db WHERE course_id = ? AND user_id = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("ss", $course_id, $user_name);
        
        if ($stmt->execute()) {
            // Redirect back with a success message
            header("Location: ../../../index.php?page=cart");
        } else {
            // Handle error
            echo "Error deleting item: " . $conn->error;
        }
        
        $stmt->close();
    }
}
?>