<main class="main-content">
    <div class="page-heading">
        <h1>My Profile</h1>
        <p>View and edit your professional information</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="card" style="max-width:560px;">
        <form method="POST" action="profile.php">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($currentUser['name']); ?>">
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" required value="<?php echo htmlspecialchars($currentUser['specialization']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" value="<?php echo htmlspecialchars($currentUser['qualification']); ?>">
                </div>
                <div class="form-group">
                    <label>Experience (years)</label>
                    <input type="number" name="experience" value="<?php echo htmlspecialchars($currentUser['experience']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Consultation Fee</label>
                    <input type="number" step="0.01" name="consultation_fee" value="<?php echo htmlspecialchars($currentUser['consultation_fee']); ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($currentUser['phone']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($currentUser['email']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</main>
