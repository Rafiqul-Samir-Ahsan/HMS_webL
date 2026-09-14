// =====================================================
// MediCore - client-side JS
// Handles: toast notifications, a custom confirm modal,
// AJAX actions (appointments, deletes), form validation,
// and a small client-side cookie for remembering the
// last-used login role on this browser.
// =====================================================

// ---------------- Toast notifications ----------------
function showToast(type, message) {
    let container = document.getElementById('mc-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'mc-toast-container';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = 'mc-toast ' + (type === 'error' ? 'mc-toast-error' : 'mc-toast-success');
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('mc-toast-show'), 10);
    setTimeout(() => {
        toast.classList.remove('mc-toast-show');
        setTimeout(() => toast.remove(), 300);
    }, 3200);
}

// ---------------- Custom confirm modal ----------------
// Replaces the browser's native confirm() with a styled modal.
// Usage: mcConfirm("Delete this doctor?").then(ok => { if (ok) ... });
function mcConfirm(message) {
    return new Promise((resolve) => {
        const overlay = document.createElement('div');
        overlay.className = 'mc-modal-overlay';
        overlay.innerHTML = `
            <div class="mc-modal">
                <p>${message}</p>
                <div class="mc-modal-actions">
                    <button type="button" class="btn btn-outline" data-choice="no">Cancel</button>
                    <button type="button" class="btn btn-danger" data-choice="yes">Confirm</button>
                </div>
            </div>`;
        document.body.appendChild(overlay);

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
                resolve(false);
            }
            const choice = e.target.getAttribute('data-choice');
            if (choice) {
                overlay.remove();
                resolve(choice === 'yes');
            }
        });
    });
}

// ---------------- Generic AJAX POST helper ----------------
// Sends a POST with URL-encoded data, expects a JSON response.
async function ajaxPost(url, params) {
    const body = new URLSearchParams(params);
    const response = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString(),
    });
    return response.json();
}

// ---------------- Doctor: approve / reject / complete appointment ----------------
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-appt-action]');
    if (!btn) return;
    e.preventDefault();

    const action = btn.getAttribute('data-appt-action'); // Approved / Rejected / Completed
    const appointmentId = btn.getAttribute('data-appointment-id');
    const row = btn.closest('tr');

    if (action === 'Rejected') {
        const ok = await mcConfirm('Reject this appointment?');
        if (!ok) return;
    }

    try {
        const result = await ajaxPost('ajax_appointment_action.php', {
            appointment_id: appointmentId,
            status: action,
        });
        if (result.success) {
            showToast('success', result.message);
            const badgeCell = row ? row.querySelector('[data-status-cell]') : null;
            const actionCell = row ? row.querySelector('[data-action-cell]') : null;
            if (badgeCell) {
                badgeCell.innerHTML = renderBadge(result.status);
            }
            if (actionCell) {
                actionCell.innerHTML = (result.status === 'Approved')
                    ? '<button class="btn btn-outline btn-sm" data-appt-action="Completed" data-appointment-id="' + appointmentId + '">Mark Completed</button>'
                    : '—';
            }
        } else {
            showToast('error', result.message);
        }
    } catch (err) {
        showToast('error', 'Network error — please try again.');
    }
});

function renderBadge(status) {
    const map = {
        Approved: 'badge-green', Pending: 'badge-yellow', Rejected: 'badge-red',
        Completed: 'badge-blue', Cancelled: 'badge-red',
    };
    const cls = map[status] || 'badge-blue';
    return '<span class="badge ' + cls + '">' + status + '</span>';
}

// ---------------- Patient: cancel appointment ----------------
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-cancel-appointment]');
    if (!btn) return;
    e.preventDefault();

    const ok = await mcConfirm('Cancel this appointment?');
    if (!ok) return;

    const appointmentId = btn.getAttribute('data-cancel-appointment');
    const row = btn.closest('tr');

    try {
        const result = await ajaxPost('ajax_cancel_appointment.php', { appointment_id: appointmentId });
        if (result.success) {
            showToast('success', result.message);
            const badgeCell = row ? row.querySelector('[data-status-cell]') : null;
            const actionCell = row ? row.querySelector('[data-action-cell]') : null;
            if (badgeCell) badgeCell.innerHTML = renderBadge('Cancelled');
            if (actionCell) actionCell.innerHTML = '—';
        } else {
            showToast('error', result.message);
        }
    } catch (err) {
        showToast('error', 'Network error — please try again.');
    }
});

// ---------------- Admin: delete doctor / patient / bed ----------------
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-delete-type]');
    if (!btn) return;
    e.preventDefault();

    const type = btn.getAttribute('data-delete-type');
    const id = btn.getAttribute('data-delete-id');
    const label = btn.getAttribute('data-delete-label') || 'this record';
    const row = btn.closest('tr');

    const ok = await mcConfirm('Remove ' + label + '? This cannot be undone.');
    if (!ok) return;

    try {
        const result = await ajaxPost('ajax_delete.php', { type: type, id: id });
        if (result.success) {
            showToast('success', result.message);
            if (row) {
                row.style.transition = 'opacity 0.25s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 250);
            }
        } else {
            showToast('error', result.message);
        }
    } catch (err) {
        showToast('error', 'Network error — please try again.');
    }
});

// ---------------- Login page: remember last-used role via a client-side cookie ----------------
(function () {
    const roleSelect = document.getElementById('role');
    if (!roleSelect) return;

    function getCookie(name) {
        const match = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
        return match ? decodeURIComponent(match[1]) : null;
    }
    function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
    }

    const savedRole = getCookie('mc_last_role');
    if (savedRole && !roleSelect.value) {
        roleSelect.value = savedRole;
    }
    roleSelect.addEventListener('change', () => setCookie('mc_last_role', roleSelect.value, 30));
})();

// ---------------- Login form validation ----------------
(function () {
    const form = document.getElementById('loginForm');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const email = form.querySelector('#email').value.trim();
        const password = form.querySelector('#password').value;
        const errorBox = document.getElementById('loginFormError');
        let message = '';

        if (email === '' || password === '') {
            message = 'Please enter both email and password.';
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            message = 'Please enter a valid email address.';
        }

        if (message) {
            e.preventDefault();
            errorBox.textContent = message;
            errorBox.style.display = 'block';
        } else {
            errorBox.style.display = 'none';
        }
    });
})();

// ---------------- Register form validation ----------------
(function () {
    const form = document.getElementById('registerForm');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const password = form.querySelector('[name="password"]').value;
        const confirm = form.querySelector('[name="confirm_password"]').value;
        const errorBox = document.getElementById('registerFormError');
        let message = '';

        if (password.length < 6) {
            message = 'Password must be at least 6 characters.';
        } else if (password !== confirm) {
            message = 'Passwords do not match.';
        }

        if (message) {
            e.preventDefault();
            errorBox.textContent = message;
            errorBox.style.display = 'block';
        } else {
            errorBox.style.display = 'none';
        }
    });
})();

// ---------------- Book appointment: simple client-side checks ----------------
(function () {
    const form = document.getElementById('bookAppointmentForm');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const reason = form.querySelector('[name="reason"]').value.trim();
        const errorBox = document.getElementById('bookApptFormError');
        if (reason.length < 3) {
            e.preventDefault();
            errorBox.textContent = 'Please briefly describe the reason for your visit.';
            errorBox.style.display = 'block';
        } else {
            errorBox.style.display = 'none';
        }
    });
})();
