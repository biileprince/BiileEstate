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
    header("Location: contacts.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM contacts WHERE id = '$id'");
$contact = mysqli_fetch_assoc($result);

if (!$contact) {
    header("Location: contacts.php");
    exit();
}
?>


<div class="container">
    <h2>Contact Message Details</h2>

    <div class="message-details">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($contact['full_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($contact['email']); ?></p>
        <p><strong>Subject:</strong> <?php echo htmlspecialchars($contact['subject']); ?></p>
        <p><strong>Submitted At:</strong> <?php echo date('M d, Y h:i A', strtotime($contact['submitted_at'])); ?></p>
        <p><strong>Message:</strong></p>
        <div class="message-content">
            <?php echo nl2br(htmlspecialchars($contact['message'])); ?>
        </div>
    </div>

    <a href="contacts.php" class="btn btn-primary">Back to Messages</a>
</div>

<?php include('../includes/footer.php'); ?>