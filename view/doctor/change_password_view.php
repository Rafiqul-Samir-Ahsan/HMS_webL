<main class="main-content">
    <div class="page-heading">
        <h1>Change Password</h1>
        <p>Update your account password</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="card" style="max-width:480px;">
        <form method="POST" action="change_password.php">
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</main>
