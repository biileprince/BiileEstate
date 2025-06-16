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

// Fetch all visit requests
$result = mysqli_query($conn, "SELECT vr.*, b.name AS building_name 
                              FROM visit_requests vr 
                              LEFT JOIN buildings b ON vr.building_id = b.building_id 
                              ORDER BY requested_at DESC");
$visits = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>



<div class="container">
    <h2>Visit Requests</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Property</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visits as $visit): ?>
                <tr>
                    <td><?php echo $visit['id']; ?></td>
                    <td><?php echo htmlspecialchars($visit['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($visit['building_name']); ?></td>
                    <td>
                        <?php echo date('M d, Y', strtotime($visit['preferred_date'])); ?>
                        at <?php echo date('h:i A', strtotime($visit['preferred_time'])); ?>
                    </td>
                    <td><?php echo ucfirst($visit['status']); ?></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($visit['requested_at'])); ?></td>
                    <td>
                        <a href="view_visit.php?id=<?php echo $visit['id']; ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>