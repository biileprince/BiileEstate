<?php include('../includes/header.php'); ?>
<?php

include('../includes/db_connect.php');

// Redirect if not admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

// Fetch all buildings
$result = mysqli_query($conn, "SELECT * FROM buildings");
$buildings = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<div class="container">
    <h2>Manage Properties</h2>
    <a href="add_building.php" class="btn btn-primary">Add New Property</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buildings as $building): ?>
                <tr>
                    <td><?php echo $building['building_id']; ?></td>
                    <td><?php echo htmlspecialchars($building['name']); ?></td>
                    <td><?php echo htmlspecialchars($building['type']); ?></td>
                    <td><?php echo htmlspecialchars($building['status']); ?></td>
                    <td><?php echo htmlspecialchars($building['location']); ?></td>
                    <td>
                        <a href="edit_building.php?id=<?php echo $building['building_id']; ?>" class="btn btn-sm btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_building.php?id=<?php echo $building['building_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>