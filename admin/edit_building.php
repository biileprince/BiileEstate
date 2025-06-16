<?php include('../includes/header.php'); ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../includes/db_connect.php');
include('../includes/functions.php');

// Redirect if not admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

// Check if building ID is provided
if (!isset($_GET['id'])) {
    header("Location: buildings.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch the building data
$result = mysqli_query($conn, "SELECT * FROM buildings WHERE building_id = '$id'");
$building = mysqli_fetch_assoc($result);

if (!$building) {
    header("Location: buildings.php");
    exit();
}

$error = '';
$success = '';

// Process form submission
if (isset($_POST['update_building'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $constructed_year = mysqli_real_escape_string($conn, $_POST['constructed_year']);
    $floors = mysqli_real_escape_string($conn, $_POST['floors']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $architect = mysqli_real_escape_string($conn, $_POST['architect']);
    $builder = mysqli_real_escape_string($conn, $_POST['builder']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $image_path = $building['image_path']; // Keep existing image by default

    // Handle file upload if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = handleFileUpload($_FILES['image']);
        if ($upload_result['success']) {
            // Delete old image if it's not the placeholder
            if ($image_path !== 'placeholder.jpg') {
                deleteUploadedFile($image_path);
            }
            $image_path = $upload_result['filename'];
        } else {
            $error = implode("<br>", $upload_result['errors']);
        }
    }

    // Update the building if no errors
    if (empty($error)) {
        $sql = "UPDATE buildings SET 
                name = '$name',
                type = '$type',
                status = '$status',
                location = '$location',
                constructed_year = '$constructed_year',
                number_of_floors = '$floors',
                total_area = '$area',
                architect = '$architect',
                builder_company = '$builder',
                description = '$description',
                image_path = '$image_path'
                WHERE building_id = '$id'";

        if (mysqli_query($conn, $sql)) {
            $success = 'Building updated successfully!';
            // Refresh building data
            $result = mysqli_query($conn, "SELECT * FROM buildings WHERE building_id = '$id'");
            $building = mysqli_fetch_assoc($result);
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>



<div class="container">
    <div class="form-container">
        <div class="form-title">
            <h2><i class="fas fa-edit"></i> Edit Property</h2>
            <p>Update the details for <?php echo htmlspecialchars($building['name']); ?></p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="edit_building.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Property Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($building['name']); ?>" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="Residential" <?php echo $building['type'] == 'Residential' ? 'selected' : ''; ?>>Residential</option>
                        <option value="Commercial" <?php echo $building['type'] == 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                        <option value="Mixed-use" <?php echo $building['type'] == 'Mixed-use' ? 'selected' : ''; ?>>Mixed-use</option>
                        <option value="Industrial" <?php echo $building['type'] == 'Industrial' ? 'selected' : ''; ?>>Industrial</option>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Planned" <?php echo $building['status'] == 'Planned' ? 'selected' : ''; ?>>Planned</option>
                        <option value="Under Construction" <?php echo $building['status'] == 'Under Construction' ? 'selected' : ''; ?>>Under Construction</option>
                        <option value="Completed" <?php echo $building['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="Rented" <?php echo $building['status'] == 'Rented' ? 'selected' : ''; ?>>Rented</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($building['location']); ?>" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="constructed_year">Constructed Year</label>
                    <input type="number" name="constructed_year" id="constructed_year" class="form-control" min="1900" max="2099" step="1" value="<?php echo htmlspecialchars($building['constructed_year']); ?>">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="floors">Number of Floors</label>
                    <input type="number" name="floors" id="floors" class="form-control" min="1" value="<?php echo htmlspecialchars($building['number_of_floors']); ?>">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="area">Total Area (sq ft)</label>
                    <input type="number" name="area" id="area" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($building['total_area']); ?>">
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="architect">Architect</label>
                    <input type="text" name="architect" id="architect" class="form-control" value="<?php echo htmlspecialchars($building['architect']); ?>">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="builder">Builder Company</label>
                    <input type="text" name="builder" id="builder" class="form-control" value="<?php echo htmlspecialchars($building['builder_company']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5"><?php echo htmlspecialchars($building['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Property Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <small class="text-muted">Leave blank to keep current image</small>

                <div class="current-image" style="margin-top: 15px;">
                    <p><strong>Current Image:</strong></p>
                    <img src="../uploads/<?php echo htmlspecialchars($building['image_path']); ?>" alt="Current Property Image" style="max-width: 300px; height: auto; border: 1px solid #ddd; padding: 5px; border-radius: 4px;">
                </div>
            </div>

            <div class="form-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                <a href="buildings.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" name="update_building" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Property
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    <?php include('../includes/footer.php'); ?>