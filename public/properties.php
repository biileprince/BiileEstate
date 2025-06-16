<?php

include('../includes/db_connect.php');

// Fetch all buildings
$result = mysqli_query($conn, "SELECT * FROM buildings");
$properties = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php include('../includes/header.php'); ?>

<section class="properties-section">
    <div class="container">
        <h2>Our Properties</h2>
        <div class="properties-grid">
            <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image">
                        <img src="../uploads/<?php echo $property['image_path']; ?>" alt="Property Image">
                    </div>
                    <div class=" property-info">
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
                        <a href="schedule_visit.php?building_id=<?php echo $property['building_id']; ?>" class="btn btn-primary">Schedule Visit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>