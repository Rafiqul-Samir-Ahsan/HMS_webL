<main class="main-content">
    <div class="page-heading">
        <h1>Upload Medical Report</h1>
        <p>Attach lab results or reports to a patient's record</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="content-grid">
        <div class="card">
            <h3>New Report</h3>
            <form method="POST" action="upload_report.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Patient *</label>
                    <select name="patient_id" required>
                        <option value="">-- Select Patient --</option>
                        <?php while ($p = mysqli_fetch_assoc($myPatients)): ?>
                        <option value="<?php echo $p['patient_id']; ?>"><?php echo htmlspecialchars($p['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Report Type *</label>
                    <input type="text" name="report_type" required placeholder="e.g. Complete Blood Count, X-Ray, Lipid Panel">
                </div>
                <div class="form-group">
                    <label>File (PDF or image)</label>
                    <input type="file" name="report_file" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes" rows="3" placeholder="Findings or remarks"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Upload Report</button>
            </form>
        </div>

        <div class="card">
            <h3>Recently Uploaded</h3>
            <?php if (mysqli_num_rows($recentReports) === 0): ?>
                <div class="empty-state">No reports uploaded yet.</div>
            <?php endif; ?>
            <?php while ($r = mysqli_fetch_assoc($recentReports)): ?>
            <div style="padding:10px 0; border-bottom:1px solid var(--border);">
                <div style="font-weight:600; font-size:0.88rem;"><?php echo htmlspecialchars($r['patient_name']); ?> — <?php echo htmlspecialchars($r['report_type']); ?></div>
                <div class="text-muted" style="font-size:0.8rem;"><?php echo date('M j, Y', strtotime($r['report_date'])); ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
