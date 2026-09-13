<main class="main-content">
    <div class="page-heading flex-between">
        <div>
            <h1>Good morning, <?php echo htmlspecialchars($currentUser['name']); ?> 👋</h1>
            <p><?php echo date('l, F j, Y'); ?> · <?php echo $todayCount; ?> appointments today</p>
        </div>
        <a href="add_prescription.php" class="btn btn-primary">+ Add Prescription</a>
    </div>

    <?php render_flash(); ?>

    <div class="stat-grid">
        <div class="card stat-card"><div class="stat-label">Today's Appointments</div><div class="stat-value"><?php echo $todayCount; ?></div></div>
        <div class="card stat-card"><div class="stat-label">Total Patients</div><div class="stat-value"><?php echo $totalPatients; ?></div></div>
        <div class="card stat-card"><div class="stat-label">Pending Requests</div><div class="stat-value"><?php echo $pendingCount; ?></div></div>
        <div class="card stat-card"><div class="stat-label">Prescriptions This Month</div><div class="stat-value"><?php echo $prescriptionsThisMonth; ?></div></div>
    </div>

    <div class="content-grid">
        <div class="card">
            <div class="section-title">
                <h3>Today's Appointments</h3>
                <a href="appointments.php" class="text-muted" style="font-size:0.82rem;">View All →</a>
            </div>
            <table>
                <tr><th>Patient</th><th>Time</th><th>Reason</th><th>Status</th><th>Action</th></tr>
                <?php if (mysqli_num_rows($todayAppointments) === 0): ?>
                    <tr><td colspan="5" class="empty-state">No appointments scheduled for today.</td></tr>
                <?php endif; ?>
                <?php while ($a = mysqli_fetch_assoc($todayAppointments)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['patient_name']); ?></td>
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

        <div class="card">
            <div class="section-title">
                <h3>Recent Patients</h3>
                <a href="patient_list.php" class="text-muted" style="font-size:0.82rem;">All →</a>
            </div>
            <?php if (mysqli_num_rows($recentPatients) === 0): ?>
                <div class="empty-state">No patients yet.</div>
            <?php endif; ?>
            <?php while ($rp = mysqli_fetch_assoc($recentPatients)): ?>
            <div class="flex-between" style="padding:10px 0; border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-weight:600; font-size:0.88rem;"><?php echo htmlspecialchars($rp['name']); ?></div>
                    <div class="text-muted" style="font-size:0.78rem;"><?php echo htmlspecialchars($rp['reason']); ?> · <?php echo date('M j, Y', strtotime($rp['appointment_date'])); ?></div>
                </div>
                <a href="patient_list.php?view=<?php echo $rp['patient_id']; ?>" class="btn btn-outline btn-sm">View</a>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
