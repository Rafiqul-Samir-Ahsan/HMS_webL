<main class="main-content">
    <div class="page-heading">
        <h1>Prescriptions</h1>
        <p>Medications prescribed by your doctors</p>
    </div>
    <?php render_flash(); ?>

    <?php if (mysqli_num_rows($prescriptions) === 0): ?>
        <div class="card empty-state">No prescriptions on file yet.</div>
    <?php endif; ?>

    <?php while ($p = mysqli_fetch_assoc($prescriptions)): ?>
    <div class="card mb-16">
        <div class="flex-between">
            <div>
                <h3 style="margin-bottom:2px;">Dr. <?php echo htmlspecialchars($p['doctor_name']); ?></h3>
                <p class="text-muted" style="margin:0; font-size:0.82rem;"><?php echo htmlspecialchars($p['specialization']); ?> · <?php echo date('M j, Y', strtotime($p['prescription_date'])); ?></p>
            </div>
        </div>
        <p class="mt-16"><strong>Medication:</strong><br><?php echo nl2br(htmlspecialchars($p['medication_details'])); ?></p>
        <?php if (!empty($p['notes'])): ?>
        <p><strong>Notes:</strong><br><?php echo nl2br(htmlspecialchars($p['notes'])); ?></p>
        <?php endif; ?>
    </div>
    <?php endwhile; ?>
</main>
