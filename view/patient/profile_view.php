<main class="main-content">
    <div class="page-heading flex-between">
        <div>
            <h1>My Profile</h1>
            <p>Your personal and contact information</p>
        </div>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    </div>
    <?php render_flash(); ?>

    <div class="card" style="max-width:560px;">
        <table>
            <tr><th>Full Name</th><td><?php echo htmlspecialchars($currentUser['name']); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($currentUser['date_of_birth']); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($currentUser['gender']); ?></td></tr>
            <tr><th>Blood Group</th><td><?php echo htmlspecialchars($currentUser['blood_group']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($currentUser['phone']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($currentUser['email']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($currentUser['address']); ?></td></tr>
            <tr><th>Patient Since</th><td><?php echo date('M j, Y', strtotime($currentUser['created_at'])); ?></td></tr>
        </table>
    </div>

    <div class="card mt-16" style="max-width:560px; border-color:#fecaca;">
        <h3 style="color:var(--red);">Danger Zone</h3>
        <p class="text-muted" style="font-size:0.85rem;">Deleting your account permanently removes your profile, appointments, prescriptions, reports, and bills.</p>
        <a href="delete_account.php" class="btn btn-danger btn-sm">Delete My Account</a>
    </div>
</main>
