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

// Fetch all contacts
$result = mysqli_query($conn, "SELECT * FROM contacts ORDER BY submitted_at DESC");
$contacts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="container">
    <h2>Contact Messages</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?php echo $contact['id']; ?></td>
                    <td><?php echo htmlspecialchars($contact['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($contact['email']); ?></td>
                    <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($contact['submitted_at'])); ?></td>
                    <td>
                        <a href="view_contact.php?id=<?php echo $contact['id']; ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>