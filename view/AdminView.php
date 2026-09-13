<?php

require_once __DIR__ . "/../controller/functions.php";
require_role("admin");

if (!isset($partial)) {
    require_once __DIR__ . "/../model/AdminModel.php";

    $totalPatients = getTotalPatients();
    $totalDoctors = getTotalDoctors();
    $todayAppointments = getTodayAppointments();
    $monthlyAppointments = getMonthlyAppointments();
    $weeklyAppointments = getWeeklyAppointments();
    $adminProfile = getAdminById($_SESSION["user_id"]);
}

?>

<?php if (isset($partial) && $partial == "doctors") { ?>

<div class="page-heading">
    <h1>Manage Doctors</h1>
    <p>Add, edit and delete doctor information.</p>
</div>

<div id="doctorMessage"></div>

<div class="content-box">

    <h2>Add Doctor</h2>

    <form id="addDoctorForm">

        <input type="hidden" name="doctor_action" value="add">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="doctorName">
        </div>
<div class="form-group">
    <label>Specialization</label>

    <select name="specialization" id="doctorSpecialization">
        <option value="">Select Specialization</option>
        <option value="Cardiology">Cardiology</option>
        <option value="Endocrinology">Endocrinology</option>
        <option value="General Medicine">General Medicine</option>
        <option value="Neurology">Neurology</option>
        <option value="Orthopedics">Orthopedics</option>
        <option value="Pediatrics">Pediatrics</option>
        <option value="Dermatology">Dermatology</option>
    </select>
</div>
<div class="form-group">
    <label>Qualification</label>

    <select name="qualification" id="doctorQualification">
        <option value="">Select Qualification</option>
        <option value="MBBS">MBBS</option>
        <option value="MBBS, FCPS">MBBS, FCPS</option>
        <option value="MBBS, MD">MBBS, MD</option>
        <option value="MBBS, MS">MBBS, MS</option>
        <option value="MBBS, MRCP">MBBS, MRCP</option>
    </select>
</div>

   
   

<div class="form-group">
    <label>Experience</label>
    <input type="number"
       name="experience"
       id="doctorExperience"
       min="0"
       placeholder="Years">
    

        <div class="form-group">
            <label>Consultation Fee</label>
           <input type="number"
       name="consultation_fee"
       id="consultation_fee"
       min="0"
       step="0.01">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" id="doctorPhone">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" id="doctorEmail">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="doctorPassword">
        </div>

        <button type="button" class="btn btn-primary" onclick="addDoctor()">
            Add Doctor
        </button>

    </form>

</div>

<div class="content-box">
    <div id="editDoctorMessage"></div>
    <h2>Doctor List</h2>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Qualification</th>
                    <th>Experience</th>
                    <th>Fee</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($doctors) > 0) { ?>

                <?php while ($doctor = mysqli_fetch_assoc($doctors)) { ?>

                    <tr>

                        <td><?php echo $doctor["doctor_id"]; ?></td>

                        <td>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($doctor["name"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="specialization" value="<?php echo htmlspecialchars($doctor["specialization"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="qualification" value="<?php echo htmlspecialchars($doctor["qualification"]); ?>">
                        </td>

                        <td>
                            <input type="number" name="experience" min="0" value="<?php echo $doctor["experience"]; ?>">
                        </td>

                        <td>
                            <input type="number" name="consultation_fee" min="0" step="0.01" value="<?php echo $doctor["consultation_fee"]; ?>">
                        </td>

                        <td>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($doctor["phone"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($doctor["email"]); ?>">
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary" onclick="updateDoctor(this, <?php echo $doctor["doctor_id"]; ?>)">
                             SAVE
                            </button>

                            <button type="button" class="btn btn-danger" onclick="deleteDoctor(<?php echo $doctor["doctor_id"]; ?>)">
                                Delete
                            </button>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="9">No doctors found.</td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
    return;
}

if (isset($partial) && $partial == "patients") {
?>

<div class="page-heading">
    <h1>Manage Patients</h1>
    <p>Add, edit and delete patient information.</p>
</div>

<div id="patientMessage"></div>

<div class="content-box">

    <h2>Add Patient</h2>

    <form id="addPatientForm">

        <input type="hidden" name="patient_action" value="add">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" id="patientName">
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" id="patientDob">
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" id="patientGender">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" id="patientPhone">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" id="patientEmail">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="patientPassword">
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address">
        </div>

        <div class="form-group">
            <label>Blood Group</label>
            <select name="blood_group">
                <option value="">Select Blood Group</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
            </select>
        </div>

        <button type="button" class="btn btn-primary" onclick="addPatient()">
            Add Patient
        </button>

    </form>

</div>

<div class="content-box">
    <div id="editPatientMessage"></div>
    <h2>Patient List</h2>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($patients) > 0) { ?>

                <?php while ($patient = mysqli_fetch_assoc($patients)) { ?>

                    <tr>

                        <td><?php echo $patient["patient_id"]; ?></td>

                        <td>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($patient["name"]); ?>">
                        </td>

                        <td>
                            <input type="date" name="date_of_birth" value="<?php echo $patient["date_of_birth"]; ?>">
                        </td>

                        <td>
                            <select name="gender">
                                <option value="Male" <?php if ($patient["gender"] == "Male") echo "selected"; ?>>Male</option>
                                <option value="Female" <?php if ($patient["gender"] == "Female") echo "selected"; ?>>Female</option>
                                <option value="Other" <?php if ($patient["gender"] == "Other") echo "selected"; ?>>Other</option>
                            </select>
                        </td>

                        <td>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($patient["phone"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($patient["email"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($patient["address"]); ?>">
                        </td>

                        <td>
                            <input type="text" name="blood_group" value="<?php echo htmlspecialchars($patient["blood_group"]); ?>">
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary" onclick="updatePatient(this, <?php echo $patient["patient_id"]; ?>)">
                                SAVE
                            </button>

                            <button type="button" class="btn btn-danger" onclick="deletePatient(<?php echo $patient["patient_id"]; ?>)">
                                Delete
                            </button>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="9">No patients found.</td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
    return;
}


if (isset($partial) && $partial == "appointments") {
?>

<div class="page-heading">
    <h1>Manage Appointments</h1>
    <p>View appointments, change status and delete appointments.</p>
</div>

<div id="appointmentMessage"></div>

<div class="content-box">

    <h2>Appointment List</h2>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($appointments) > 0) { ?>

                <?php while ($appointment = mysqli_fetch_assoc($appointments)) { ?>

                    <tr>

                        <td>
                            <?php echo $appointment["appointment_id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($appointment["patient_name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($appointment["doctor_name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($appointment["appointment_date"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($appointment["appointment_time"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($appointment["reason"]); ?>
                        </td>

                        <td>
                            <select name="status">
                                <option value="Pending" <?php if ($appointment["status"] == "Pending") echo "selected"; ?>>Pending</option>
                                <option value="Approved" <?php if ($appointment["status"] == "Approved") echo "selected"; ?>>Approved</option>
                                <option value="Rejected" <?php if ($appointment["status"] == "Rejected") echo "selected"; ?>>Rejected</option>
                                <option value="Completed" <?php if ($appointment["status"] == "Completed") echo "selected"; ?>>Completed</option>
                                <option value="Cancelled" <?php if ($appointment["status"] == "Cancelled") echo "selected"; ?>>Cancelled</option>
                            </select>
                        </td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-primary"
                                onclick="updateAppointmentStatus(this, <?php echo $appointment["appointment_id"]; ?>)"
                            >
                                Update
                            </button>

                            <button
                                type="button"
                                class="btn btn-danger"
                                onclick="deleteAppointment(<?php echo $appointment["appointment_id"]; ?>)"
                            >
                                Delete
                            </button>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="8">No appointments found.</td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
    return;
}


if (isset($partial) && $partial == "wardbeds") {
?>

<div class="page-heading">
    <h1>Ward & Bed Management</h1>
    <p>Add beds, change bed status and delete beds.</p>
</div>

<div id="wardMessage"></div>

<div class="content-box">

    <h2>Add Ward & Bed</h2>

    <form id="addWardBedForm">

        <input type="hidden" name="ward_action" value="add">

        <div class="form-group">
            <label>Ward Name</label>
            <input type="text" name="ward_name" id="wardName">
        </div>

        <div class="form-group">
            <label>Bed Number</label>
            <input type="text" name="bed_number" id="bedNumber">
        </div>

        <div class="form-group">
            <label>Bed Type</label>
            <select name="bed_type" id="bedType">
                <option value="">Select Bed Type</option>
                <option value="General">General</option>
                <option value="Private">Private</option>
                <option value="Deluxe">Deluxe</option>
                <option value="ICU">ICU</option>
            </select>
        </div>

        <button type="button" class="btn btn-primary" onclick="addWardBed()">
            Add Bed
        </button>

    </form>

</div>

<div class="content-box">

    <h2>Ward & Bed List</h2>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ward</th>
                    <th>Bed Number</th>
                    <th>Bed Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($wardBeds) > 0) { ?>

                <?php while ($wardBed = mysqli_fetch_assoc($wardBeds)) { ?>

                    <tr>

                        <td><?php echo $wardBed["ward_bed_id"]; ?></td>
                        <td><?php echo htmlspecialchars($wardBed["ward_name"]); ?></td>
                        <td><?php echo htmlspecialchars($wardBed["bed_number"]); ?></td>
                        <td><?php echo htmlspecialchars($wardBed["bed_type"]); ?></td>

                        <td>
                            <select name="bed_status">
                                <option value="Available" <?php if ($wardBed["bed_status"] == "Available") echo "selected"; ?>>Available</option>
                                <option value="Occupied" <?php if ($wardBed["bed_status"] == "Occupied") echo "selected"; ?>>Occupied</option>
                                <option value="Maintenance" <?php if ($wardBed["bed_status"] == "Maintenance") echo "selected"; ?>>Maintenance</option>
                            </select>
                        </td>

                        <td>
                            <button type="button" class="btn btn-primary" onclick="updateWardBedStatus(this, <?php echo $wardBed["ward_bed_id"]; ?>)">
                                Update
                            </button>

                           <button type="button" class="btn btn-danger" onclick="
                                    if (this.closest('tr').querySelector('[name=bed_status]').value == 'Occupied') {
                                        alert('Occupied bed cannot be deleted.');
                                        return false;
                                    }
                                    deleteWardBed(<?php echo $wardBed['ward_bed_id']; ?>);
                                    ">
                                Delete
                            </button>
                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="6">No ward or bed found.</td>
                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php
    return;
    
}
if (isset($partial) && $partial == "changePassword") {
?>

<div class="page-heading">
    <h1>Change Password</h1>
    <p>Update your account password.</p>
</div>

<div id="passwordMessage"></div>

<div class="content-box">

    <form id="changePasswordForm">

        <input type="hidden" name="password_action" value="change">

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" id="currentPassword">
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" id="newPassword">
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirmPassword">
        </div>

        <button type="button" class="btn btn-primary" onclick="changeAdminPassword()">
            Change Password
        </button>

    </form>

</div>

<?php
    return;
}

$adminName = $_SESSION["name"];
$totalPatients = isset($totalPatients) ? $totalPatients : 0;
$totalDoctors = isset($totalDoctors) ? $totalDoctors : 0;
$todayAppointments = isset($todayAppointments) ? $todayAppointments : 0;
$monthlyAppointments = isset($monthlyAppointments) ? $monthlyAppointments : 0;
$weeklyAppointments = isset($weeklyAppointments) ? $weeklyAppointments : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../view/css/admin_dashboard_manage_doctors.css?v=2">
    <title>MediCore Admin Dashboard</title>
</head>

<body>

<div class="admin-container">

    <aside class="sidebar">

        <div class="logo">MediCore</div>

        <p class="sidebar-title">Administration</p>

        <ul class="sidebar-menu">
            <li>
                <a href="#" onclick="showDashboard(); return false;">
                    Dashboard
                </a>
            </li>

            <li>
                <a href="#" onclick="loadDoctors(); return false;">
                    Manage Doctors
                </a>
            </li>

            <li>
                <a href="#" onclick="loadPatients(); return false;">
                    Manage Patients
                </a>
            </li>

            <li>
                <a href="#" onclick="loadAppointments(); return false;">
                    Manage Appointments
                </a>
            </li>

            <li>
                <a href="#" onclick="loadWardBeds(); return false;">
                    Ward & Bed Mgmt
                </a>
            </li>
        </ul>

        <div class="sidebar-bottom">

            <form action="../controller/auth/logout.php" method="POST">
                <button type="submit" name="logout" class="logout-btn">
                    Log Out
                </button>
            </form>

            <p class="sidebar-title">Account</p>

           <a href="#" class="change-password"
            onclick="loadChangePassword(); return false;">
                Change Password
            </a>

         <div class="user-box" onclick="openAdminProfile()">
    <strong>
        <?php echo htmlspecialchars($adminName); ?>
    </strong>
    <br>
    Admin
</div>

        </div>

    </aside>

    <main class="main-area">

        <header class="topbar">

            <input
                type="text"
                class="search-box"
                placeholder="Search records, doctors, patients..."
            >

            <div class="topbar-right">
                <span><?php echo date("d M Y"); ?></span>
              
            </div>

        </header>

        <section class="main-content" id="mainContent">

            <div class="page-heading">
                <h1>
                    Good morning, <?php echo htmlspecialchars($adminName); ?> 👋
                </h1>
                <p>System overview</p>
            </div>

            <div class="dashboard-grid">

                <div class="stat-card">
                    <h3>Total Patients</h3>
                    <div class="number"><?php echo $totalPatients; ?></div>
                </div>

                <div class="stat-card">
                    <h3>Total Doctors</h3>
                    <div class="number"><?php echo $totalDoctors; ?></div>
                </div>

                <div class="stat-card tall-card">
                    <h3>Today's Appointments</h3>
                    <div class="number"><?php echo $todayAppointments; ?></div>
                </div>

                <div class="stat-card large-card">
                    <h3>Total Appointments This Month</h3>
                    <div class="number"><?php echo $monthlyAppointments; ?></div>
                </div>

            </div>

            <div class="content-box">

                <h2>Appointments per Week</h2>

                <?php if (count($weeklyAppointments) > 0) { ?>

                    <?php
                    $maxAppointment = max($weeklyAppointments);

                    if ($maxAppointment == 0) {
                        $maxAppointment = 1;
                    }
                    ?>

                    <div class="weekly-chart">

                        <?php foreach ($weeklyAppointments as $day => $count) { ?>

                            <?php
                            $height = ($count / $maxAppointment) * 160;
                            ?>

                            <div class="bar-item">
                                <span class="bar-number"><?php echo $count; ?></span>

                                <div
                                    class="bar"
                                    style="height: <?php echo $height; ?>px;"
                                ></div>

                                <span class="bar-day">
                                    <?php echo htmlspecialchars($day); ?>
                                </span>
                            </div>

                        <?php } ?>

                    </div>

                <?php } ?>

            </div>

        </section>

    </main>

</div>
<div id="profileModal" class="profile-modal">

    <div class="profile-modal-content">

        <button type="button" class="profile-close" onclick="closeAdminProfile()">
            ×
        </button>

        <h2>My Profile</h2>

        <form id="adminProfileForm">

            <input type="hidden" name="profile_action" value="update">

            <div class="form-group">
                <label>Name</label>
                <input
                    type="text"
                    name="name"
                    id="profileName"
                    value="<?php echo htmlspecialchars($adminProfile["name"]); ?>"
                >
            </div>

            <div class="form-group">
                <label>Age</label>
                <input
                    type="number"
                    name="age"
                    id="profileAge"
                    min="1"
                    max="120"
                    value="<?php echo htmlspecialchars($adminProfile["age"] ?? ""); ?>"
                >
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input
                    type="text"
                    name="phone"
                    id="profilePhone"
                    value="<?php echo htmlspecialchars($adminProfile["phone"] ?? ""); ?>"
                >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input
                    type="text"
                    value="<?php echo htmlspecialchars($adminProfile["email"]); ?>"
                    readonly
                >
            </div>

            <div class="form-group">
                <label>Role</label>
                <input type="text" value="Admin" readonly>
            </div>

            <button
                type="button"
                class="btn btn-primary"
                onclick="updateAdminProfile()"
            >
                Save Changes
            </button>

        </form>

    </div>

</div>
<script src="../view/js/script.js?v=2"></script>
<script src="../view/js/admin.js?v=2"></script>

</body>
</html>
