<main class="main-content">
    <div class="page-heading flex-between">
        <div>
            <h1>My Appointments</h1>
            <p>Track the status of your bookings</p>
        </div>
        <a href="book_appointment.php" class="btn btn-primary">+ Book Appointment</a>
    </div>
    <?php render_flash(); ?>

    <div class="card">
        <table>
            <tr><th>Doctor</th><th>Specialization</th><th>Date</th><th>Time</th><th>Reason</th><th>Status</th><th>Action</th></tr>
            <?php if (mysqli_num_rows($appointments) === 0): ?>
                <tr><td colspan="7" class="empty-state">You haven't booked any appointments yet.</td></tr>
            <?php endif; ?>
            <?php while ($a = mysqli_fetch_assoc($appointments)): ?>
            <tr>
                <td><?php echo htmlspecialchars($a['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($a['specialization']); ?></td>
                <td><?php echo date('M j, Y', strtotime($a['appointment_date'])); ?></td>
                <td><?php echo date('h:i A', strtotime($a['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($a['reason']); ?></td>
                <td data-status-cell><?php echo status_badge($a['status']); ?></td>
                <td data-action-cell>
                    <?php if ($a['status'] === 'Pending'): ?>
                        <button type="button" class="btn btn-outline btn-sm" data-cancel-appointment="<?php echo $a['appointment_id']; ?>">Cancel</button>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
