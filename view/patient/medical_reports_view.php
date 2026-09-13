<main class="main-content">
    <div class="page-heading">
        <h1>Medical Reports</h1>
        <p>Lab results and reports uploaded by your doctors</p>
    </div>
    <?php render_flash(); ?>

    <div class="card">
        <table>
            <tr><th>Report Type</th><th>Uploaded By</th><th>Date</th><th>Notes</th><th>File</th></tr>
            <?php if (mysqli_num_rows($reports) === 0): ?>
                <tr><td colspan="5" class="empty-state">No medical reports available yet.</td></tr>
            <?php endif; ?>
            <?php while ($r = mysqli_fetch_assoc($reports)): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['report_type']); ?></td>
                <td>Dr. <?php echo htmlspecialchars($r['doctor_name']); ?></td>
                <td><?php echo date('M j, Y', strtotime($r['report_date'])); ?></td>
                <td><?php echo htmlspecialchars($r['notes']); ?></td>
                <td>
                    <?php if (!empty($r['file_path'])): ?>
                        <a href="../../<?php echo htmlspecialchars($r['file_path']); ?>" target="_blank" class="btn btn-outline btn-sm">Download</a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
