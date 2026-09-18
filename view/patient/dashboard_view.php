<main class="main-content">
    <div class="page-heading flex-between">
        <div></div>
        <a href="book_appointment.php" class="btn btn-primary">+ Book Appointment</a>
    </div>

    <?php render_flash(); ?>

    <div class="welcome-banner">
        <div>
            <div class="tag">WELCOME BACK</div>
            <h2>Hello, <?php echo htmlspecialchars($currentUser['name']); ?> 👋</h2>
            <?php if ($nextAppt): ?>
            <p>Your next appointment is with <?php echo htmlspecialchars($nextAppt['doctor_name']); ?> on <?php echo date('M j, Y', strtotime($nextAppt['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($nextAppt['appointment_time'])); ?>.</p>
            <?php else: ?>
            <p>You have no upcoming appointments scheduled.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="stat-grid">
        <div class="card stat-card">
            <div class="stat-label">Profile</div>
            <div class="stat-value" style="font-size:1.1rem;"><?php echo htmlspecialchars($currentUser['blood_group']); ?> · <?php echo htmlspecialchars($currentUser['gender']); ?></div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">Upcoming Visits</div>
            <div class="stat-value"><?php echo $upcomingCount; ?></div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">Prescriptions on File</div>
            <div class="stat-value"><?php echo $activePrescriptions; ?></div>
        </div>
        <div class="card stat-card">
            <div class="stat-label">Outstanding Balance</div>
            <div class="stat-value">$<?php echo number_format($outstanding, 2); ?></div>
        </div>
    </div>

    <div class="content-grid">
        <div class="card">
            <div class="section-title">
                <h3>Billing</h3>
                <a href="my_bills.php" class="text-muted" style="font-size:0.82rem;">View All →</a>
            </div>
            <?php if (mysqli_num_rows($recentBills) === 0): ?>
                <div class="empty-state">No billing history yet.</div>
            <?php endif; ?>
            <?php while ($b = mysqli_fetch_assoc($recentBills)): ?>
            <div class="flex-between" style="padding:9px 0; border-bottom:1px solid var(--border);">
                <div style="font-size:0.85rem;">
                    Bill #<?php echo $b['bill_id']; ?> · <?php echo date('M j, Y', strtotime($b['bill_date'])); ?><br>
                    <span class="text-muted" style="font-size:0.78rem;">$<?php echo number_format($b['amount'], 2); ?></span>
                </div>
                <?php echo status_badge($b['payment_status']); ?>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="card">
            <div class="section-title">
                <h3>Upcoming Appointments</h3>
                <a href="my_appointments.php" class="text-muted" style="font-size:0.82rem;">All →</a>
            </div>
            <?php if (mysqli_num_rows($upcomingAppointments) === 0): ?>
                <div class="empty-state">No upcoming appointments.</div>
            <?php endif; ?>
            <?php while ($a = mysqli_fetch_assoc($upcomingAppointments)): ?>
            <div class="flex-between" style="padding:9px 0; border-bottom:1px solid var(--border);">
                <div style="font-size:0.85rem;">
                    Dr. <?php echo htmlspecialchars($a['doctor_name']); ?><br>
                    <span class="text-muted" style="font-size:0.78rem;"><?php echo htmlspecialchars($a['specialization']); ?> · <?php echo date('M j', strtotime($a['appointment_date'])); ?></span>
                </div>
                <?php echo status_badge($a['status']); ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
