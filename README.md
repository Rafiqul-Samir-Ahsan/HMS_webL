# MediCore — Hospital Management System

A full-stack Hospital Management System built with **HTML, CSS, JavaScript, and plain procedural PHP + MySQL**, based on the provided ER diagram, use case diagram, and MediCore dashboard mockups.

## ⚠️ If you already imported the database before this update

This version adds one new column (`remember_token`) to `patient`, `doctor`, and `admin`, used for the "Remember Me" login feature. If you already have `hospital_db` set up from before, run this once in phpMyAdmin's **SQL** tab (no need to re-import or drop anything):

```sql
ALTER TABLE patient ADD COLUMN remember_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE doctor  ADD COLUMN remember_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE admin   ADD COLUMN remember_token VARCHAR(255) DEFAULT NULL;
```

If this is a fresh install, just import `database/hospital_db.sql` as normal — it already includes this column.

## Setup (XAMPP / WAMP / MAMP)

1. Install XAMPP (or WAMP/MAMP) and start **Apache** and **MySQL**.
2. Copy the whole `hms` folder into your server's web root:
   - XAMPP (Windows): `C:\xampp\htdocs\hms`
   - XAMPP (Mac/Linux): `/Applications/XAMPP/htdocs/hms` or `/opt/lampp/htdocs/hms`
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`), create nothing manually — just go to the **Import** tab and import `database/hospital_db.sql`. It creates the `hospital_db` database and tables, and seeds sample data automatically.
4. Check `config/db.php` — the defaults (`root` user, no password, `localhost`) match a stock XAMPP install. Change them if your MySQL setup is different.
5. Visit `http://localhost/hms/` in your browser. You should land on the login page.

## Demo Logins

Every seeded account uses the password: **password123**

| Role    | Email                  | Password    |
|---------|-------------------------|-------------|
| Admin   | admin@medicore.com      | password123 |
| Doctor  | anwar@medicore.com      | password123 |
| Doctor  | sadia@medicore.com      | password123 |
| Patient | patient@medicore.com    | password123 |

New patients can also self-register from the login page (matches the use case diagram — only patients have a public "Register" use case; doctor and admin accounts are created by an Admin).

## Folder structure

```
hms/
├── config/db.php              # database connection
├── includes/                  # shared header, footer, sidebars, helper functions
├── assets/css/style.css       # MediCore theme (teal header, dark sidebar, toasts, modal)
├── assets/js/script.js        # all client-side JS + AJAX calls (see below)
├── auth/                      # login, register, logout
├── admin/                     # dashboard, manage doctors/patients, ward & bed mgmt, profile, ajax_delete.php
├── doctor/                    # dashboard, appointments, patient list, prescriptions, reports, ajax_appointment_action.php
├── patient/                   # dashboard, book/view appointments, prescriptions, reports, bills, profile, ajax_cancel_appointment.php
├── uploads/reports/           # uploaded medical report files land here
├── database/hospital_db.sql   # schema + seed data
└── index.php                  # redirects to login or the right dashboard
```

## How it maps to your diagrams

- **ER Diagram**: every table (`patient`, `doctor`, `admin`, `appointment`, `prescription`, `medical_report`, `bill`, `ward_bed`) and relationship is implemented as-is. Two extensions: (1) a `password` column was added to `patient` and `doctor` (the ER diagram only showed one on `admin`) so all three roles can authenticate, and (2) a `remember_token` column was added to all three for the "Remember Me" login feature.
- **Use Case Diagram**: Login/Logout/Change Password are shared flows available to all three roles (`includes/functions.php` + per-role sidebars). Register is patient-only. Every other use case (Book/View Appointments, View Prescriptions/Reports/Bills for patients; Manage Appointments, View Patient Details, Create Prescription, Upload Medical Report for doctors; Manage Doctors, Manage Patients, Manage Ward/Bed for admin) has its own page.
- **Dashboards**: `admin/dashboard.php`, `doctor/dashboard.php`, and `patient/dashboard.php` follow the layout, stat cards, and color scheme (teal top bar `#14b8a6`, dark sidebar) from your mockups. The admin dashboard's weekly chart uses Chart.js.

## JavaScript, AJAX, JSON & Cookies — where and why

Everything client-side lives in one file, `assets/js/script.js`, so it's easy to read top to bottom. It's organized into clearly-commented sections:

- **Cookie — "Remember Me"** (`includes/functions.php` + `auth/login.php`): checking the box on login sets a cookie containing the role, user ID, and a random 32-byte token. The token is also saved in that user's database row (`remember_token` column), so the cookie by itself is useless to anyone — it only works if it matches what's stored server-side. Unchecking it (or logging out) clears both. This is real persistent login, not just a UI toggle.
- **Cookie — remembered login role**: a small extra convenience cookie remembers which role (Patient/Doctor/Admin) you last logged in as, so the dropdown pre-selects it next time.
- **AJAX + JSON — appointment actions**: on the doctor's dashboard/appointments page, clicking Approve/Reject/Mark Completed sends a `fetch()` POST to `doctor/ajax_appointment_action.php`, which updates the database and returns a JSON response (`{success, status, message}`). The page then updates just that row's badge and buttons — no reload. Same pattern for a patient cancelling their own appointment (`patient/ajax_cancel_appointment.php`).
- **AJAX + JSON — deleting records**: in the admin panel, deleting a doctor, patient, or bed calls `admin/ajax_delete.php`, which returns JSON and the row fades out — again, no page reload.
- **JS form validation**: login, register, and book-appointment forms do a quick client-side check (matching passwords, valid email format, non-empty reason) before submitting, with the error shown inline. The server still re-validates everything independently — client-side checks are just for a faster, friendlier experience, never the only line of defense.
- **UI polish**: a small toast notification system and a custom confirm dialog (`mcConfirm()`) replace the browser's plain `alert()`/`confirm()` popups, styled to match the MediCore theme.

## Notes / things you may want to extend

- File uploads for medical reports are stored under `uploads/reports/` — make sure that folder is writable by the web server.
- Bill creation isn't wired to a specific UI yet since it wasn't in your use case diagram (only "View Bill/Payment Details" was, for patients). Right now bills are seeded via SQL. If you want, I can add a "Create/Manage Bills" screen for admin or doctor next.
- All passwords are hashed with PHP's `password_hash()` (bcrypt) — never stored in plain text.
- Forms use `mysqli_real_escape_string` + prepared-style escaping via the `clean()` helper to reduce SQL injection risk. For a production app you'd want to fully move to parameterized queries (`mysqli_prepare`), but this keeps the code simple and readable for coursework.
- Admin search (Manage Patients page) is a plain server-side search on page reload, not AJAX — kept simple on purpose since it works fine without JavaScript at all.

## Code organization (Controller / Model / View)

The project is split into exactly three folders at the root:

```
hms/
├── controller/
│   ├── functions.php   (session, redirect, flash-message, and role-guard helpers)
│   ├── admin/          (dashboard.php, manage_doctors.php, manage_patients.php, ...)
│   ├── doctor/         (dashboard.php, appointments.php, add_prescription.php, ...)
│   ├── patient/        (dashboard.php, book_appointment.php, my_bills.php, ...)
│   └── auth/           (login.php, register.php, logout.php)
├── model/
│   ├── auth_model.php       (login lookup, "remember me", get_current_user_row)
│   ├── doctor_model.php, patient_model.php, admin_model.php
│   ├── appointment_model.php, prescription_model.php, medical_report_model.php
│   └── bill_model.php, ward_bed_model.php
├── view/
│   ├── layouts/       (header.php, footer.php — shared page shell)
│   ├── partials/      (sidebar_admin.php, sidebar_doctor.php, sidebar_patient.php)
│   ├── admin/, doctor/, patient/, auth/   (one *_view.php per page)
├── config/db.php      (unchanged)
├── assets/, database/, uploads/  (unchanged)
└── index.php          (redirects to controller/auth/login.php or your dashboard)
```

**Nothing lives in an `includes/` folder anymore** — it used to hold `functions.php`
and the header/footer/sidebar HTML, which didn't belong to just one of the three
layers:
- The HTML parts (`header.php`, `footer.php`, `sidebar_*.php`) moved into `view/`,
  since that's exactly what they are — shared page templates.
- Most of `functions.php` (session handling, `redirect()`, `require_role()`,
  flash messages) is controller-support code with no HTML or SQL in it, so it's
  now `controller/functions.php` — every controller requires it first, same as before.
- The handful of functions in `functions.php` that ran actual queries
  (`get_current_user_row()`, and the "remember me" cookie functions) moved into
  `model/auth_model.php`, since they touch the database. `controller/functions.php`
  requires that model file internally, so nothing else had to change in the
  individual pages.

**URLs live under `controller/`** — e.g. `controller/admin/dashboard.php`,
`controller/patient/book_appointment.php`. Visiting `index.php` at the project root
still works and sends you to the right place.

**How each page is put together:**
- **`controller/<role>/<page>.php`** — what you'd open in the browser. Handles the
  request (`$_POST`/`$_GET`), calls model functions, decides what happens next.
- **`model/<entity>_model.php`** — the database functions. Same `mysqli` queries as
  your original code, just named and grouped by table.
- **`view/<role>/<page>_view.php`** — the HTML for that page, unchanged.
- **`view/layouts/`** and **`view/partials/`** — the shared header/footer/sidebar,
  included by every controller the same way `includes/` used to be.

A controller pulls everything together like this:

```php
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../model/doctor_model.php';
require_role('admin');

$doctors = get_all_doctors($conn);          // model

include __DIR__ . '/../../view/layouts/header.php';
include __DIR__ . '/../../view/partials/sidebar_admin.php';
include __DIR__ . '/../../view/admin/manage_doctors_view.php';   // view
include __DIR__ . '/../../view/layouts/footer.php';
```

Nothing else changed: `config/db.php` and the database schema are exactly as they
were. To add a new page: new file in `controller/<role>/`, a model function if it
needs new queries, and a view file in `view/<role>/`.
