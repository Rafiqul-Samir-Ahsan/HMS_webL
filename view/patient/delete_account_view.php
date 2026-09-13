<main class="main-content">
    <div class="page-heading">
        <h1>Delete Account</h1>
        <p>This action is permanent and cannot be undone</p>
    </div>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="card" style="max-width:480px; border-color:#fecaca;">
        <p>Deleting your account will permanently remove your profile, appointments, prescriptions, medical reports, and bills. This cannot be undone.</p>
        <form method="POST" action="delete_account.php">
            <div class="form-group">
                <label>Enter your password to confirm</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-danger">Permanently Delete My Account</button>
            <a href="profile.php" class="btn btn-outline">Cancel</a>
        </form>
    </div>
</main>
