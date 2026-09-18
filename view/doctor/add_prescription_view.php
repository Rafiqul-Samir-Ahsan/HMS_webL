<main class="main-content">
    <div class="page-heading">
        <h1>Add Prescription</h1>
        <p>Write a prescription for one of your patients</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="content-grid">
        <div class="card">
            <h3>New Prescription</h3>
            <form method="POST" action="add_prescription.php">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patient_id" required>
                        <option value="">-- Select Patient --</option>
                        <?php while ($p = mysqli_fetch_assoc($myPatients)): ?>
                        <option value="<?php echo $p['patient_id']; ?>" <?php echo $preselect==$p['patient_id']?'selected':''; ?>><?php echo htmlspecialchars($p['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Medication Details *</label>
                    <textarea name="medication_details" rows="4" required placeholder="e.g. Metformin 500mg - 1 tablet twice daily after meals"></textarea>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="3" placeholder="Additional instructions or follow-up notes"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Prescription</button>
            </form>
        </div>

        <div class="card">
            <h3>Recently Written</h3>
            <?php if (mysqli_num_rows($recentPrescriptions) === 0): ?>
                <div class="empty-state">No prescriptions written yet.</div>
            <?php endif; ?>
            <?php while ($rp = mysqli_fetch_assoc($recentPrescriptions)): ?>
            <div style="padding:10px 0; border-bottom:1px solid var(--border);">
                <div style="font-weight:600; font-size:0.88rem;"><?php echo htmlspecialchars($rp['patient_name']); ?> — <?php echo date('M j, Y', strtotime($rp['prescription_date'])); ?></div>
                <div class="text-muted" style="font-size:0.8rem;"><?php echo htmlspecialchars($rp['medication_details']); ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
