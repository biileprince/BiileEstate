<?php include('../includes/header.php'); ?>
<?php

include('../includes/db_connect.php');

// Redirect if not admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

// Count data for dashboard
$building_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM buildings"))['count'];
$contact_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM contacts"))['count'];
$visit_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visit_requests"))['count'];
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users"))['count'];

// Recent visits
$recent_visits = mysqli_query($conn, "SELECT vr.*, b.name AS building_name 
                                     FROM visit_requests vr 
                                     LEFT JOIN buildings b ON vr.building_id = b.building_id 
                                     ORDER BY requested_at DESC LIMIT 5");
?>



<div class="container dashboard">
    <h2>Admin Dashboard</h2>

    <div class="dashboard-stats">
        <div class="stat-card">
            <i class="fas fa-building"></i>
            <h3><?php echo $building_count; ?></h3>
            <p>Properties</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-envelope"></i>
            <h3><?php echo $contact_count; ?></h3>
            <p>Messages</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-calendar-check"></i>
            <h3><?php echo $visit_count; ?></h3>
            <p>Visit Requests</p>
        </div>

        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?php echo $user_count; ?></h3>
            <p>Users</p>
        </div>
    </div>

    <div class="dashboard-actions">
        <div class="dashboard-card">
            <h3><i class="fas fa-building"></i> Quick Actions</h3>
            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 20px;">
                <a href="buildings.php" class="btn btn-primary">
                    <i class="fas fa-list"></i> Manage Properties
                </a>
                <a href="add_building.php" class="btn btn-secondary">
                    <i class="fas fa-plus"></i> Add New Property
                </a>
                <a href="contacts.php" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> View Messages
                </a>
                <a href="visits.php" class="btn btn-secondary">
                    <i class="fas fa-calendar"></i> View Visit Requests
                </a>
            </div>
        </div>

        <div class="dashboard-card">
            <h3><i class="fas fa-history"></i> Recent Visit Requests</h3>
            <div class="table-container" style="margin-top: 20px;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Property</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($visit = mysqli_fetch_assoc($recent_visits)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($visit['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($visit['building_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($visit['preferred_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>