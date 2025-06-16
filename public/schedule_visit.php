<?php include('../includes/header.php'); ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/db_connect.php');

$success = '';
$error = '';

// Fetch buildings for dropdown
$buildings_result = mysqli_query($conn, "SELECT building_id, name FROM buildings");
$buildings = mysqli_fetch_all($buildings_result, MYSQLI_ASSOC);

if (isset($_POST['schedule'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $building_id = mysqli_real_escape_string($conn, $_POST['building_id']);
    $preferred_date = mysqli_real_escape_string($conn, $_POST['preferred_date']);
    $preferred_time = mysqli_real_escape_string($conn, $_POST['preferred_time']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO visit_requests (full_name, email, phone_number, building_id, preferred_date, preferred_time, message) 
            VALUES ('$full_name', '$email', '$phone', '$building_id', '$preferred_date', '$preferred_time', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = 'Your visit has been scheduled successfully!';
    } else {
        $error = 'Error: ' . mysqli_error($conn);
    }
}
?>


<div class="container">
    <div class="visit-form-container">
        <h2>Schedule a Property Visit</h2>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="schedule_visit.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" class="form-control">
            </div>
            <div class="form-group">
                <label for="building_id">Select Property</label>
                <select name="building_id" id="building_id" class="form-control" required>
                    <option value="">Choose a property</option>
                    <?php foreach ($buildings as $building): ?>
                        <option value="<?php echo $building['building_id']; ?>">
                            <?php echo htmlspecialchars($building['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="preferred_date">Preferred Date</label>
                <input type="date" name="preferred_date" id="preferred_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="preferred_time">Preferred Time</label>
                <input type="time" name="preferred_time" id="preferred_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="message">Additional Notes</label>
                <textarea name="message" id="message" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" name="schedule" class="btn btn-primary">Schedule Visit</button>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>