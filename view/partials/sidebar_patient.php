<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="sidebar-section-label">MAIN</div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?php echo $cur=='dashboard.php'?'active':''; ?>">🏠 Dashboard</a>
        <a href="book_appointment.php" class="<?php echo $cur=='book_appointment.php'?'active':''; ?>">📅 Book Appointment</a>
        <a href="my_appointments.php" class="<?php echo $cur=='my_appointments.php'?'active':''; ?>">🗓️ My Appointments</a>
        <a href="prescriptions.php" class="<?php echo $cur=='prescriptions.php'?'active':''; ?>">💊 Prescriptions</a>
        <a href="medical_reports.php" class="<?php echo $cur=='medical_reports.php'?'active':''; ?>">🧾 Medical Reports</a>
        <a href="my_bills.php" class="<?php echo $cur=='my_bills.php'?'active':''; ?>">💳 My Bills</a>
    </nav>
    <div class="sidebar-bottom">
        <a href="../auth/logout.php" class="logout-btn">Log Out</a>
        <div class="sidebar-section-label">ACCOUNT</div>
        <a href="profile.php" class="<?php echo $cur=='profile.php'?'active':''; ?>">👤 View Profile</a>
        <a href="edit_profile.php" class="<?php echo $cur=='edit_profile.php'?'active':''; ?>">✏️ Edit Profile</a>
        <a href="change_password.php" class="<?php echo $cur=='change_password.php'?'active':''; ?>">🔒 Change Password</a>
    </div>
</aside>
