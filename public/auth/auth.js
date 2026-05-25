const API_BASE = '/school-management-System/public/index.php/api';

async function handleAuth(e, type) {
    e.preventDefault();

    let payload = {};
    if (type === 'login') {
        payload.username = document.getElementById('loginUsername').value;
        payload.password = document.getElementById('loginPassword').value;
    } else {
        payload.full_name = document.getElementById('regFullName').value;
        payload.email = document.getElementById('regEmail').value;
        payload.username = document.getElementById('regUsername').value;
        payload.password = document.getElementById('regPassword').value;
    }

    if (type === 'register') {
        const intendedPlan = localStorage.getItem('intendedPlan') || '1';
        payload.plan_id = intendedPlan;
    }

    try {
        const response = await fetch(`${API_BASE}/auth/${type}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            if (type === 'login') {
                localStorage.setItem('token', data.token);
                localStorage.setItem('school_id', data.school_id || '');
                window.location.href = '../dashboard.html';
            } else {
                showAlert('authAlert', 'Registration successful! Please sign in.', 'success');
                setTimeout(() => { window.location = '/auth/login' }, 3000);
            }
        } else {
            showAlert('authAlert', data.message || 'Authentication failed', 'error');
        }
    } catch (err) {
        showAlert('authAlert', 'Network error. Please try again.', 'error');
        console.log(err);
    }
}

function showAlert(elementId, message, type = 'success') {
    const alertEl = document.getElementById(elementId);
    if (!alertEl) return;
    alertEl.textContent = message;
    alertEl.className = `alert ${type}`;
    alertEl.style.display = 'block';
    setTimeout(() => { alertEl.style.display = 'none'; }, 5000);
}