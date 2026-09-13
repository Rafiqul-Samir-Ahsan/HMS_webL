<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="sidebar-section-label">CLINICAL</div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?php echo $cur=='dashboard.php'?'active':''; ?>">🏠 Dashboard</a>
        <a href="appointments.php" class="<?php echo $cur=='appointments.php'?'active':''; ?>">📅 Appointments</a>
        <a href="patient_list.php" class="<?php echo $cur=='patient_list.php'?'active':''; ?>">📋 Patient List</a>
        <a href="add_prescription.php" class="<?php echo $cur=='add_prescription.php'?'active':''; ?>">💊 Add Prescription</a>
        <a href="upload_report.php" class="<?php echo $cur=='upload_report.php'?'active':''; ?>">📄 Upload Report</a>
    </nav>
    <div class="sidebar-bottom">
        <a href="../auth/logout.php" class="logout-btn">Log Out</a>
        <div class="sidebar-section-label">ACCOUNT</div>
        <a href="profile.php" class="<?php echo $cur=='profile.php'?'active':''; ?>">👤 My Profile</a>
        <a href="change_password.php" class="<?php echo $cur=='change_password.php'?'active':''; ?>">🔒 Change Password</a>
    </div>
</aside>
