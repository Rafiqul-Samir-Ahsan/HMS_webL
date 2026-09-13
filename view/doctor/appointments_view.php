<main class="main-content">
    <div class="page-heading">
        <h1>Appointments</h1>
        <p>Manage all your patient appointments</p>
    </div>
    <?php render_flash(); ?>

    <div class="card mb-16">
        <?php foreach (['All','Pending','Approved','Rejected','Completed','Cancelled'] as $f): ?>
            <a href="appointments.php?status=<?php echo $f; ?>" class="btn <?php echo $filter===$f?'btn-primary':'btn-outline'; ?> btn-sm"><?php echo $f; ?></a>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <table>
            <tr><th>Patient</th><th>Date</th><th>Time</th><th>Reason</th><th>Status</th><th>Action</th></tr>
            <?php if (mysqli_num_rows($appointments) === 0): ?>
                <tr><td colspan="6" class="empty-state">No appointments found.</td></tr>
            <?php endif; ?>
            <?php while ($a = mysqli_fetch_assoc($appointments)): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['patient_name']); ?><br><span class="text-muted" style="font-size:0.75rem;"><?php echo htmlspecialchars($a['patient_phone']); ?></span></td>
                <td><?php echo date('M j, Y', strtotime($a['appointment_date'])); ?></td>
                <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($a['reason']); ?></td>
                <td data-status-cell><?php echo status_badge($a['status']); ?></td>
                <td data-action-cell>
                    <?php if ($a['status'] === 'Pending'): ?>
                        <button type="button" class="btn btn-primary btn-sm" data-appt-action="Approved" data-appointment-id="<?php echo $a['appointment_id']; ?>">Approve</button>
                        <button type="button" class="btn btn-danger btn-sm" data-appt-action="Rejected" data-appointment-id="<?php echo $a['appointment_id']; ?>">Reject</button>
                    <?php elseif ($a['status'] === 'Approved'): ?>
                        <button type="button" class="btn btn-outline btn-sm" data-appt-action="Completed" data-appointment-id="<?php echo $a['appointment_id']; ?>">Mark Completed</button>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
