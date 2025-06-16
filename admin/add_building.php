<?php include('../includes/header.php'); ?>
<?php

include('../includes/db_connect.php');
include('../includes/functions.php');

// Redirect if not admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../public/index.php");
    exit();
}

$error = '';
$success = '';

if (isset($_POST['add_building'])) {
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

    $image_path = 'placeholder.jpg'; // default

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_result = handleFileUpload($_FILES['image']);
        if ($upload_result['success']) {
            $image_path = $upload_result['filename'];
        } else {
            $error = implode("<br>", $upload_result['errors']);
        }
    }

    if (empty($error)) {
        $sql = "INSERT INTO buildings (name, type, status, location, constructed_year, number_of_floors, total_area, architect, builder_company, description, image_path) 
                VALUES ('$name', '$type', '$status', '$location', '$constructed_year', '$floors', '$area', '$architect', '$builder', '$description', '$image_path')";

        if (mysqli_query($conn, $sql)) {
            $success = 'Property added successfully!';
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    }
}
?>



<div class="container">
    <div class="form-container">
        <div class="form-title">
            <h2><i class="fas fa-plus-circle"></i> Add New Property</h2>
            <p>Fill in the details for the new property</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="add_building.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Property Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="Residential">Residential</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Mixed-use">Mixed-use</option>
                    <option value="Industrial">Industrial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Planned">Planned</option>
                    <option value="Under Construction">Under Construction</option>
                    <option value="Completed">Completed</option>
                    <option value="Rented">Rented</option>
                </select>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="constructed_year">Constructed Year</label>
                    <input type="number" name="constructed_year" id="constructed_year" class="form-control" min="1900" max="2099" step="1">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="floors">Number of Floors</label>
                    <input type="number" name="floors" id="floors" class="form-control" min="1">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="area">Total Area (sq ft)</label>
                    <input type="number" name="area" id="area" class="form-control" step="0.01" min="0">
                </div>
            </div>

            <div class="form-row" style="display: flex; gap: 15px;">
                <div class="form-group" style="flex: 1;">
                    <label for="architect">Architect</label>
                    <input type="text" name="architect" id="architect" class="form-control">
                </div>

                <div class="form-group" style="flex: 1;">
                    <label for="builder">Builder Company</label>
                    <input type="text" name="builder" id="builder" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label for="image">Property Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                <small class="text-muted">Max file size: 5MB (JPG, PNG, GIF, WEBP)</small>
            </div>

            <button type="submit" name="add_building" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Property
            </button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>