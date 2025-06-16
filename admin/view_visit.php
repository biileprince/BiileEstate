 <?php include('../includes/header.php'); ?>

 <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include('../includes/db_connect.php');

    // Redirect if not admin
    if ($_SESSION['role'] !== 'admin') {
        header("Location: ../public/index.php");
        exit();
    }

    if (!isset($_GET['id'])) {
        header("Location: visits.php");
        exit();
    }

    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $result = mysqli_query($conn, "SELECT vr.*, b.name AS building_name 
                              FROM visit_requests vr 
                              LEFT JOIN buildings b ON vr.building_id = b.building_id 
                              WHERE vr.id = '$id'");
    $visit = mysqli_fetch_assoc($result);

    if (!$visit) {
        header("Location: visits.php");
        exit();
    }
    ?>


 <div class="container">
     <h2>Visit Request Details</h2>

     <div class="visit-details">
         <p><strong>Name:</strong> <?php echo htmlspecialchars($visit['full_name']); ?></p>
         <p><strong>Email:</strong> <?php echo htmlspecialchars($visit['email']); ?></p>
         <p><strong>Phone:</strong> <?php echo htmlspecialchars($visit['phone_number']); ?></p>
         <p><strong>Property:</strong> <?php echo htmlspecialchars($visit['building_name']); ?></p>
         <p><strong>Preferred Date:</strong> <?php echo date('M d, Y', strtotime($visit['preferred_date'])); ?></p>
         <p><strong>Preferred Time:</strong> <?php echo date('h:i A', strtotime($visit['preferred_time'])); ?></p>
         <p><strong>Status:</strong> <?php echo ucfirst($visit['status']); ?></p>
         <p><strong>Requested At:</strong> <?php echo date('M d, Y h:i A', strtotime($visit['requested_at'])); ?></p>
         <p><strong>Additional Notes:</strong></p>
         <div class="message-content">
             <?php echo nl2br(htmlspecialchars($visit['message'])); ?>
         </div>
     </div>

     <a href="visits.php" class="btn btn-primary">Back to Visits</a>
 </div>

 <?php include('../includes/footer.php'); ?>