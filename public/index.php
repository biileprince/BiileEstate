<?php

include('../includes/db_connect.php');

// Fetch featured properties
$result = mysqli_query($conn, "SELECT * FROM buildings ORDER BY created_at DESC LIMIT 3");
$properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php include('../includes/header.php'); ?>

<section class="hero">
    <div class="container">
        <h1>Premium Real Estate Management</h1>
        <p>Streamline your property portfolio with our comprehensive management solution</p>
        <div class="hero-buttons">
            <a href="/realestate/public/properties.php" class="btn btn-primary">View Properties</a>
            <a href="/realestate/public/schedule_visit.php" class="btn btn-secondary">Schedule Visit</a>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="feature-card">
            <i class="fas fa-building"></i>
            <h3>Property Management</h3>
            <p>Easily track and manage all your properties in one place</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-calendar-check"></i>
            <h3>Visit Scheduling</h3>
            <p>Streamline property visits and client appointments</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-comments"></i>
            <h3>Client Communication</h3>
            <p>Manage all client inquiries and communications efficiently</p>
        </div>
    </div>
</section>

<section class="properties-preview">
    <div class="container">
        <h2>Featured Properties</h2>
        <div class="properties-grid">
            <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image" style="background-image: url('../assets/placeholder.jpg')"></div>
                    <div class="property-info">
                        <h3><?php echo htmlspecialchars($property['name']); ?></h3>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                        <div class="property-meta">
                            <span><?php echo htmlspecialchars($property['type']); ?></span>
                            <span><?php echo htmlspecialchars($property['status']); ?></span>
                        </div>
                        <div class="property-details">
                            <div><i class="fas fa-layer-group"></i> <?php echo $property['number_of_floors']; ?> Floors</div>
                            <div><i class="fas fa-calendar"></i> <?php echo $property['constructed_year']; ?></div>
                        </div>
                        <a href="properties.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>