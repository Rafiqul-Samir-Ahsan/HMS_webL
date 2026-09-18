<?php
// Expects $pageTitle and optional $currentUser to be set by the including page
if (!isset($pageTitle)) { $pageTitle = 'MediCore'; }
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$roleLabel = ucfirst($role);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?> | MediCore</title>
<link rel="stylesheet" href="<?php echo isset($assetPath) ? $assetPath : ''; ?>view/css/style.css">
</head>
<body>
<header class="topbar">
    <div class="topbar-left">
        <span class="logo">🏥 MediCore</span>
    </div>
    <div class="topbar-search">
        <input type="text" placeholder="Search records, doctors, patients...">
    </div>
    <div class="topbar-right">
        <span class="role-pill"><?php echo htmlspecialchars($roleLabel); ?></span>
        <span class="notif-bell">🔔</span>
        <?php if (isset($currentUser) && $currentUser): ?>
        <div class="user-chip">
            <span class="avatar"><?php echo strtoupper(substr($currentUser['name'], 0, 2)); ?></span>
            <span class="user-info">
                <span class="user-name"><?php echo htmlspecialchars($currentUser['name']); ?></span>
                <span class="user-id">ID: <?php echo htmlspecialchars($_SESSION['user_id']); ?></span>
            </span>
        </div>
        <?php endif; ?>
    </div>
</header>
<div class="layout">
