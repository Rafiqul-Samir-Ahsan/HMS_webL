<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | MediCore</title>
<link rel="stylesheet" href="../../view/css/style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-box">
        <div class="logo-row">🏥 MediCore</div>
        <div class="subtitle">Sign in to your account</div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
        <?php endif; ?>
        <?php render_flash(); ?>

        <form method="POST" action="login.php" id="loginForm" novalidate>
            <div class="form-group">
                <label for="role">Login as</label>
                <select name="role" id="role" required>
                    <option value="patient" <?php echo (isset($_POST['role']) && $_POST['role']=='patient')?'selected':''; ?>>Patient</option>
                    <option value="doctor" <?php echo (isset($_POST['role']) && $_POST['role']=='doctor')?'selected':''; ?>>Doctor</option>
                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role']=='admin')?'selected':''; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group" style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="remember" id="remember" value="1" style="width:auto;">
                <label for="remember" style="margin:0; font-weight:500;">Remember me for 30 days</label>
            </div>
            <div id="loginFormError" class="alert alert-error" style="display:none;"></div>
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>

        <div class="auth-footer">
            New patient? <a href="register.php">Create an account</a>
        </div>
    </div>
</div>
<script src="../../view/js/script.js"></script>
</body>
</html>
