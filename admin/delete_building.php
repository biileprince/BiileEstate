<?php
session_start();
include('../includes/db_connect.php');
include('../includes/functions.php');

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Get building image
    $result = mysqli_query($conn, "SELECT image_path FROM buildings WHERE building_id = '$id'");
    $building = mysqli_fetch_assoc($result);

    if ($building) {
        // Delete image if not placeholder
        if ($building['image_path'] && $building['image_path'] !== 'placeholder.jpg') {
            deleteUploadedFile($building['image_path']);
        }

        // Delete building record
        $sql = "DELETE FROM buildings WHERE building_id = '$id'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = 'Building deleted successfully!';
        } else {
            $_SESSION['error'] = 'Error deleting building: ' . mysqli_error($conn);
        }
    }
}

header("Location: buildings.php");
exit();
