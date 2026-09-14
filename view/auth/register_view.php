<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | MediCore</title>
<link rel="stylesheet" href="../../view/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-box" style="max-width: 520px;">
        <div class="logo-row">🏥 MediCore</div>
        <div class="subtitle">Create your patient account</div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm" novalidate>
            <div class="form-row">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($old['name']); ?>">
                </div>
                <div class="form-group">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" required value="<?php echo htmlspecialchars($old['date_of_birth']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Gender *</label>
                    <select name="gender" required>
                        <option value="Male" <?php echo $old['gender']=='Male'?'selected':''; ?>>Male</option>
                        <option value="Female" <?php echo $old['gender']=='Female'?'selected':''; ?>>Female</option>
                        <option value="Other" <?php echo $old['gender']=='Other'?'selected':''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Blood Group</label>
                    <input type="text" name="blood_group" placeholder="e.g. B+" value="<?php echo htmlspecialchars($old['blood_group']); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="text" name="phone" required value="<?php echo htmlspecialchars($old['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($old['email']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($old['address']); ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" required>
                </div>
            </div>
            <div id="registerFormError" class="alert alert-error" style="display:none;"></div>
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</div>
<script src="../../controller/js/script.js"></script>
</body>
</html>
