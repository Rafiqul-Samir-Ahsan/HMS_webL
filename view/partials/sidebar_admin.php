<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="sidebar">
    <div class="sidebar-section-label">ADMINISTRATION</div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="<?php echo $cur=='dashboard.php'?'active':''; ?>">🏠 Dashboard</a>
        <a href="manage_doctors.php" class="<?php echo $cur=='manage_doctors.php'?'active':''; ?>">🩺 Manage Doctors</a>
        <a href="manage_patients.php" class="<?php echo $cur=='manage_patients.php'?'active':''; ?>">🧑‍🤝‍🧑 Manage Patients</a>
        <a href="manage_wards.php" class="<?php echo $cur=='manage_wards.php'?'active':''; ?>">🛏️ Ward &amp; Bed Mgmt</a>
    </nav>
    <div class="sidebar-bottom">
        <a href="../auth/logout.php" class="logout-btn">Log Out</a>
        <div class="sidebar-section-label">ACCOUNT</div>
        <a href="profile.php" class="<?php echo $cur=='profile.php'?'active':''; ?>">👤 My Profile</a>
        <a href="change_password.php" class="<?php echo $cur=='change_password.php'?'active':''; ?>">🔒 Change Password</a>
    </div>
</aside>
