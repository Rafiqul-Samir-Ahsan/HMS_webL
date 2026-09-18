<main class="main-content">
    <div class="page-heading">
        <h1>Edit Profile</h1>
        <p>Keep your information up to date</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="card" style="max-width:560px;">
        <form method="POST" action="edit_profile.php">
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($currentUser['name']); ?>">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($currentUser['date_of_birth']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="Male" <?php echo $currentUser['gender']=='Male'?'selected':''; ?>>Male</option>
                        <option value="Female" <?php echo $currentUser['gender']=='Female'?'selected':''; ?>>Female</option>
                        <option value="Other" <?php echo $currentUser['gender']=='Other'?'selected':''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group" value="<?php echo htmlspecialchars($currentUser['blood_group']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($currentUser['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($currentUser['email']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($currentUser['address']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="profile.php" class="btn btn-outline">Cancel</a>
        </form>
    </div>
</main>
