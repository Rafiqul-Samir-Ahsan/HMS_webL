<main class="main-content">
    <div class="page-heading">
        <h1>Book Appointment</h1>
        <p>Choose a doctor and a convenient time</p>
    </div>
    <?php render_flash(); ?>
    <?php if (!empty($errors)): ?><div class="alert alert-error"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div><?php endif; ?>

    <div class="content-grid">
        <div class="card">
            <h3>Appointment Details</h3>
            <form method="POST" action="book_appointment.php" id="bookAppointmentForm" novalidate>
                <div class="form-group">
                    <label>Doctor *</label>
                    <select name="doctor_id" required>
                        <option value="">-- Select Doctor --</option>
                        <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
                        <option value="<?php echo $d['doctor_id']; ?>"><?php echo htmlspecialchars($d['name']); ?> — <?php echo htmlspecialchars($d['specialization']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Time *</label>
                        <input type="time" name="appointment_time" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Reason for Visit *</label>
                    <textarea name="reason" rows="3" required placeholder="Briefly describe your symptoms or reason for the visit"></textarea>
                </div>
                <div id="bookApptFormError" class="alert alert-error" style="display:none;"></div>
                <button type="submit" class="btn btn-primary">Request Appointment</button>
            </form>
        </div>

        <div class="card">
            <h3>Our Doctors</h3>
            <?php mysqli_data_seek($doctors, 0); ?>
            <?php while ($d = mysqli_fetch_assoc($doctors)): ?>
            <div style="padding:10px 0; border-bottom:1px solid var(--border);">
                <div style="font-weight:600; font-size:0.88rem;"><?php echo htmlspecialchars($d['name']); ?></div>
                <div class="text-muted" style="font-size:0.8rem;"><?php echo htmlspecialchars($d['specialization']); ?> · <?php echo htmlspecialchars($d['qualification']); ?></div>
                <div class="text-muted" style="font-size:0.78rem;">Fee: $<?php echo number_format($d['consultation_fee'], 2); ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
