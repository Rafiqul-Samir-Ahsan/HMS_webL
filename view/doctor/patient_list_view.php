<main class="main-content">
    <div class="page-heading">
        <h1>Patient List</h1>
        <p>Patients who have booked appointments with you</p>
    </div>
    <?php render_flash(); ?>

    <?php if ($viewPatient): ?>
    <div class="card mb-16">
        <div class="flex-between">
            <div>
                <h3 style="margin-bottom:4px;"><?php echo htmlspecialchars($viewPatient['name']); ?></h3>
                <p class="text-muted" style="margin:0;">
                    <?php echo htmlspecialchars($viewPatient['gender']); ?> ·
                    DOB <?php echo htmlspecialchars($viewPatient['date_of_birth']); ?> ·
                    Blood Group <?php echo htmlspecialchars($viewPatient['blood_group']); ?> ·
                    <?php echo htmlspecialchars($viewPatient['phone']); ?> ·
                    <?php echo htmlspecialchars($viewPatient['email']); ?>
                </p>
            </div>
            <a href="add_prescription.php?patient_id=<?php echo $viewPatient['patient_id']; ?>" class="btn btn-primary btn-sm">+ Prescription</a>
        </div>
        <h4 class="mt-16">Appointment History</h4>
        <table>
            <tr><th>Date</th><th>Time</th><th>Reason</th><th>Status</th></tr>
            <?php while ($h = mysqli_fetch_assoc($history)): ?>
            <tr>
                <td><?php echo date('M j, Y', strtotime($h['appointment_date'])); ?></td>
                <td><?php echo date('h:i A', strtotime($h['appointment_time'])); ?></td>
                <td><?php echo htmlspecialchars($h['reason']); ?></td>
                <td><?php echo status_badge($h['status']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="card">
        <table>
            <tr><th>Name</th><th>Gender</th><th>Blood Group</th><th>Phone</th><th>Email</th><th>Action</th></tr>
            <?php if (mysqli_num_rows($patients) === 0): ?>
                <tr><td colspan="6" class="empty-state">No patients yet.</td></tr>
            <?php endif; ?>
            <?php while ($p = mysqli_fetch_assoc($patients)): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['gender']); ?></td>
                <td><?php echo htmlspecialchars($p['blood_group']); ?></td>
                <td><?php echo htmlspecialchars($p['phone']); ?></td>
                <td><?php echo htmlspecialchars($p['email']); ?></td>
                <td><a href="patient_list.php?view=<?php echo $p['patient_id']; ?>" class="btn btn-outline btn-sm">View</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</main>
