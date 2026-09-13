var adminMainContent = document.getElementById("mainContent");
var dashboardHTML = adminMainContent ? adminMainContent.innerHTML : "";

function showDashboard()
{
    var mainContent = document.getElementById("mainContent");

    if (mainContent)
    {
        mainContent.innerHTML = dashboardHTML;
    }
}

function loadDoctors()
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                document.getElementById("mainContent").innerHTML = data.html;
            }
        }
    };

    xhttp.open("GET", "../controller/AdminController.php?action=doctors", true);
    xhttp.send();
}

function addDoctor()
{
    showDoctorMessage("");

 var name = document.getElementById("doctorName").value.trim();
var specialization = document.getElementById("doctorSpecialization").value;
var qualification = document.getElementById("doctorQualification").value;
var experience = document.getElementById("doctorExperience").value.trim();
var phone = document.getElementById("doctorPhone").value.trim();
var email = document.getElementById("doctorEmail").value.trim();
var password = document.getElementById("doctorPassword").value;
var consultation_fee = document.getElementById("consultation_fee").value;

    if (name == "")
{
    showDoctorMessage("Name is required.");
    return;
}

if (specialization == "")
{
    showDoctorMessage("Specialization is required.");
    return;
}

if (qualification == "")
{
    showDoctorMessage("Qualification is required.");
    return;
}

if (experience == "")
{
    showDoctorMessage("Experience is required.");
    return;
}

if (isNaN(experience) || Number(experience) < 0)
{
    showDoctorMessage("Experience must be a valid number.");
    return;
}
if (consultation_fee == "")
{
    showDoctorMessage("Consultation fee is required.");
    return;
}

if (isNaN(consultation_fee) || Number(consultation_fee) < 0)
{
    showDoctorMessage("Consultation fee must be a valid number.");
    return;
}
if (phone == "")
{
    showDoctorMessage("Phone is required.");
    return;
}

if (!/^01[0-9]{9,}$/.test(phone))
{
    showDoctorMessage("Phone must start with 01 and contain at least 11 digits.");
    return;
}

if (email == "")
{
    showDoctorMessage("Email is required.");
    return;
}

var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (!emailPattern.test(email))
{
    showDoctorMessage("Enter a valid email address.");
    return;
}

if (password == "")
{
    showDoctorMessage("Password is required.");
    return;
}

if (password.length < 6)
{
    showDoctorMessage("Password must be at least 6 characters.");
    return;
}

    var form = document.getElementById("addDoctorForm");
    var formData = new FormData(form);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadDoctors();
            }
            else
            {
                showDoctorMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function updateDoctor(button, doctorId)
{
    var row = button.closest("tr");

    var name = row.querySelector('[name="name"]').value.trim();
    var specialization = row.querySelector('[name="specialization"]').value.trim();
    var qualification = row.querySelector('[name="qualification"]').value.trim();
    var experience = row.querySelector('[name="experience"]').value.trim();
    var consultationFee = row.querySelector('[name="consultation_fee"]').value.trim();
    var phone = row.querySelector('[name="phone"]').value.trim();
    var email = row.querySelector('[name="email"]').value.trim();

    if (name == "")
{
    showEditDoctorMessage("Name is required.");
    return;
}

if (specialization == "")
{
    showEditDoctorMessage("Specialization is required.");
    return;
}

if (qualification == "")
{
    showEditDoctorMessage("Qualification is required.");
    return;
}

if (experience == "")
{
    showEditDoctorMessage("Experience is required.");
    return;
}

if (isNaN(experience) || Number(experience) < 0)
{
    showEditDoctorMessage("Experience must be a valid number.");
    return;
}

if (consultationFee == "")
{
    showEditDoctorMessage("Consultation fee is required.");
    return;
}

if (isNaN(consultationFee) || Number(consultationFee) < 0)
{
    showEditDoctorMessage("Consultation fee must be a valid number.");
    return;
}

if (phone == "")
{
    showEditDoctorMessage("Phone is required.");
    return;
}

if (!/^01[0-9]{9,}$/.test(phone))
{
    showEditDoctorMessage("Phone must start with 01 and contain at least 11 digits.");
    return;
}

if (email == "")
{
    showDoctorMessage("Email is required.");
    return;
}

var emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;

if (!emailPattern.test(email))
{
    showEditDoctorMessage("Enter a valid email address.");
    return;
}

    var formData = new FormData();

    formData.append("doctor_action", "update");
    formData.append("doctor_id", doctorId);
    formData.append("name", name);
    formData.append("specialization", specialization);
    formData.append("qualification", qualification);
    formData.append("experience", experience);
    formData.append("consultation_fee", consultationFee);
    formData.append("phone", phone);
    formData.append("email", email);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadDoctors();
            }
            else
            {
                showEditDoctorMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function deleteDoctor(doctorId)
{
    if (!confirm("Delete this doctor?"))
    {
        return;
    }

    var formData = new FormData();

    formData.append("doctor_action", "delete");
    formData.append("doctor_id", doctorId);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadDoctors();
            }
            else
            {
                showEditDoctorMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}
function showEditDoctorMessage(message)
{
    var box = document.getElementById("editDoctorMessage");

    if (box)
    {
        box.innerHTML = message;
    }
}
function showDoctorMessage(message)
{
    var box = document.getElementById("doctorMessage");

    if (box)
    {
        box.innerHTML = message;
    }
    else
    {
        alert(message);
    }
}

function loadPatients()
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                document.getElementById("mainContent").innerHTML = data.html;
            }
        }
    };

    xhttp.open("GET", "../controller/AdminController.php?action=patients", true);
    xhttp.send();
}

function addPatient()
{
    showPatientMessage("");

    var name = document.getElementById("patientName").value.trim();
    var dateOfBirth = document.getElementById("patientDob").value;
    var gender = document.getElementById("patientGender").value;
    var phone = document.getElementById("patientPhone").value.trim();
    var email = document.getElementById("patientEmail").value.trim();
    var password = document.getElementById("patientPassword").value;

    if (name == "")
    {
        showPatientMessage("Name is required.");
        return;
    }

    if (dateOfBirth == "")
    {
        showPatientMessage("Date of birth is required.");
        return;
    }

   var selectedDate = new Date(dateOfBirth + "T00:00:00");

        var today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate > today)
        {
            showPatientMessage("Date of birth cannot be in the future.");
            return;
        }
    if (gender == "")
    {
        showPatientMessage("Gender is required.");
        return;
    }

    if (phone == "")
    {
        showPatientMessage("Phone is required.");
        return;
    }

    if (!/^01[0-9]{9,}$/.test(phone))
    {
        showPatientMessage("Phone must start with 01 and contain at least 11 digits.");
        return;
    }

    if (email == "")
    {
        showPatientMessage("Email is required.");
        return;
    }

    var emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;

    if (!emailPattern.test(email))
    {
        showPatientMessage("Enter a valid email address.");
        return;
    }

    if (password == "")
    {
        showPatientMessage("Password is required.");
        return;
    }

    if (password.length < 6)
    {
        showPatientMessage("Password must be at least 6 characters.");
        return;
    }

    var form = document.getElementById("addPatientForm");
    var formData = new FormData(form);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadPatients();
            }
            else
            {
                showPatientMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}


function updatePatient(button, patientId)
{
    showEditPatientMessage("");

    var row = button.closest("tr");

    var name = row.querySelector('[name="name"]').value.trim();
    var dateOfBirth = row.querySelector('[name="date_of_birth"]').value;
    var gender = row.querySelector('[name="gender"]').value;
    var phone = row.querySelector('[name="phone"]').value.trim();
    var email = row.querySelector('[name="email"]').value.trim();
    var address = row.querySelector('[name="address"]').value.trim();
    var bloodGroup = row.querySelector('[name="blood_group"]').value.trim();

    if (name == "")
    {
        showEditPatientMessage("Name is required.");
        return;
    }

    if (dateOfBirth == "")
    {
        showEditPatientMessage("Date of birth is required.");
        return;
    }

    var selectedDate = new Date(dateOfBirth + "T00:00:00");
    var today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate > today)
    {
        showEditPatientMessage("Date of birth cannot be in the future.");
        return;
    }

    if (gender == "")
    {
        showEditPatientMessage("Gender is required.");
        return;
    }

    if (phone == "")
    {
        showEditPatientMessage("Phone is required.");
        return;
    }

    if (!/^01[0-9]{9,}$/.test(phone))
    {
        showEditPatientMessage("Phone must start with 01 and contain at least 11 digits.");
        return;
    }

    if (email == "")
    {
        showEditPatientMessage("Email is required.");
        return;
    }

    var emailPattern = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;

    if (!emailPattern.test(email))
    {
        showEditPatientMessage("Enter a valid email address.");
        return;
    }

    if (address == "")
    {
        showEditPatientMessage("Address is required.");
        return;
    }

    if (bloodGroup == "")
    {
        showEditPatientMessage("Blood group is required.");
        return;
    }

    var allowedBloodGroups = [
        "A+", "A-",
        "B+", "B-",
        "AB+", "AB-",
        "O+", "O-"
    ];

    if (!allowedBloodGroups.includes(bloodGroup))
    {
        showEditPatientMessage("Enter a valid blood group.");
        return;
    }

    var formData = new FormData();

    formData.append("patient_action", "update");
    formData.append("patient_id", patientId);
    formData.append("name", name);
    formData.append("date_of_birth", dateOfBirth);
    formData.append("gender", gender);
    formData.append("phone", phone);
    formData.append("email", email);
    formData.append("address", address);
    formData.append("blood_group", bloodGroup);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadPatients();
            }
            else
            {
                showEditPatientMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function showPatientMessage(message)
{
    var box = document.getElementById("patientMessage");

    if (box)
    {
        box.innerHTML = message;
    }
    else
    {
        alert(message);
    }
}

function showEditPatientMessage(message)
{
    var box = document.getElementById("editPatientMessage");

    if (box)
    {
        box.innerHTML = message;
    }
}
function loadAppointments()
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                document.getElementById("mainContent").innerHTML = data.html;
            }
        }
    };

    xhttp.open("GET", "../controller/AdminController.php?action=appointments", true);
    xhttp.send();
}

function updateAppointmentStatus(button, appointmentId)
{
    var row = button.closest("tr");
    var status = row.querySelector('[name="status"]').value;

    var formData = new FormData();

    formData.append("appointment_action", "updateStatus");
    formData.append("appointment_id", appointmentId);
    formData.append("status", status);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadAppointments();
            }
            else
            {
                showAppointmentMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function deleteAppointment(appointmentId)
{
    if (!confirm("Delete this appointment?"))
    {
        return;
    }

    var formData = new FormData();

    formData.append("appointment_action", "delete");
    formData.append("appointment_id", appointmentId);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadAppointments();
            }
            else
            {
                showAppointmentMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function showAppointmentMessage(message)
{
    var box = document.getElementById("appointmentMessage");

    if (box)
    {
        box.innerHTML = message;
    }
    else
    {
        alert(message);
    }
}


function loadWardBeds()
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                document.getElementById("mainContent").innerHTML = data.html;
            }
        }
    };

    xhttp.open("GET", "../controller/AdminController.php?action=wardbeds", true);
    xhttp.send();
}

function addWardBed()
{
    showWardMessage("");

    var wardName = document.getElementById("wardName").value.trim();
    var bedNumber = document.getElementById("bedNumber").value.trim();
    var bedType = document.getElementById("bedType").value;

    if (wardName == "")
    {
        showWardMessage("Ward name is required.");
        return;
    }

    if (bedNumber == "")
    {
        showWardMessage("Bed number is required.");
        return;
    }

    if (bedType == "")
    {
        showWardMessage("Bed type is required.");
        return;
    }

    var form = document.getElementById("addWardBedForm");
    var formData = new FormData(form);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadWardBeds();
            }
            else
            {
                showWardMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function updateWardBedStatus(button, wardBedId)
{
    var row = button.closest("tr");
    var status = row.querySelector('[name="bed_status"]').value;

    var formData = new FormData();

    formData.append("ward_action", "status");
    formData.append("ward_bed_id", wardBedId);
    formData.append("bed_status", status);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadWardBeds();
            }
            else
            {
                showWardMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function deleteWardBed(wardBedId)
{
    if (!confirm("Delete this ward and bed?"))
    {
        return;
    }

    var formData = new FormData();

    formData.append("ward_action", "delete");
    formData.append("ward_bed_id", wardBedId);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                alert(data.message);
                loadWardBeds();
            }
            else
            {
                showWardMessage(data.message);
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}

function showWardMessage(message)
{
    var box = document.getElementById("wardMessage");

    if (box)
    {
        box.innerHTML = message;
    }
    else
    {
        alert(message);
    }
}
function loadChangePassword()
{
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            if (data.success)
            {
                document.getElementById("mainContent").innerHTML = data.html;
            }
        }
    };

    xhttp.open("GET", "../controller/AdminController.php?action=changePassword", true);
    xhttp.send();
}

function changeAdminPassword()
{
    var currentPassword = document.getElementById("currentPassword").value;
    var newPassword = document.getElementById("newPassword").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (currentPassword == "" || newPassword == "" || confirmPassword == "")
    {
        alert("All fields are required.");
        return;
    }

    if (newPassword.length < 6)
    {
        alert("New password must be at least 6 characters.");
        return;
    }

    if (newPassword != confirmPassword)
    {
        alert("Passwords do not match.");
        return;
    }

    var form = document.getElementById("changePasswordForm");
    var formData = new FormData(form);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);
            alert(data.message);

            if (data.success)
            {
                showDashboard();
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);

}
function openAdminProfile()
{
    document.getElementById("profileModal").style.display = "flex";
}

function closeAdminProfile()
{
    document.getElementById("profileModal").style.display = "none";
}
function updateAdminProfile()
{
    var name = document.getElementById("profileName").value.trim();
    var age = document.getElementById("profileAge").value.trim();
    var phone = document.getElementById("profilePhone").value.trim();

    if (name == "" || age == "" || phone == "")
    {
        alert("Name, age and phone are required.");
        return;
    }

    if (isNaN(age) || Number(age) < 1 || Number(age) > 120)
    {
        alert("Enter a valid age.");
        return;
    }

    if (!/^01[0-9]{9,}$/.test(phone))
    {
        alert("Phone must start with 01 and contain at least 11 digits.");
        return;
    }

    var form = document.getElementById("adminProfileForm");
    var formData = new FormData(form);

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var data = JSON.parse(this.responseText);

            alert(data.message);

            if (data.success)
            {
                closeAdminProfile();
                location.reload();
            }
        }
    };

    xhttp.open("POST", "../controller/AdminController.php", true);
    xhttp.send(formData);
}