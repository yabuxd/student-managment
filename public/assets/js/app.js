const API_BASE = '/school managment System/public/index.php/api';

// --- UI Utilities ---
function showAlert(elementId, message, type = 'success') {
    const alertEl = document.getElementById(elementId);
    if (!alertEl) return;
    alertEl.textContent = message;
    alertEl.className = `alert ${type}`;
    alertEl.style.display = 'block';
    setTimeout(() => { alertEl.style.display = 'none'; }, 5000);
}

function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// --- Landing Page Checkout Mock ---
let selectedPlanId = null;
function mockCheckout(planId, planName) {
    selectedPlanId = planId;
    document.getElementById('checkoutPlanName').textContent = planName;
    openModal('checkoutModal');
}

function processCheckout() {
    alert('Mock Payment Successful! Redirecting to setup...');
    closeModal('checkoutModal');
    // Save plan intent locally
    localStorage.setItem('intendedPlan', selectedPlanId);
    window.location.href = 'auth.html';
}

// --- Auth Handling ---
function toggleAuth(type) {
    document.getElementById('loginForm').style.display = type === 'login' ? 'block' : 'none';
    document.getElementById('registerForm').style.display = type === 'register' ? 'block' : 'none';
}

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
                window.location.href = 'dashboard.html';
            } else {
                showAlert('authAlert', 'Registration successful! Please sign in.', 'success');
                toggleAuth('login');
            }
        } else {
            showAlert('authAlert', data.message || 'Authentication failed', 'error');
        }
    } catch (err) {
        showAlert('authAlert', 'Network error. Please try again.', 'error');
    }
}

function checkAuthStatus() {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = 'auth.html';
    }
}

function logout() {
    localStorage.clear();
    window.location.href = 'index.html';
}

function getSessionData() {
    const token = localStorage.getItem('token');
    if (!token) return null;
    try {
        return JSON.parse(atob(token));
    } catch {
        return null;
    }
}

// --- Dashboard ---
function loadDashboardData() {
    const session = getSessionData();
    const schoolId = localStorage.getItem('school_id');

    if (!schoolId || schoolId === "null" || schoolId === "") {
        document.getElementById('noSchoolState').style.display = 'block';

        // Pre-select plan if they came from landing page
        const intendedPlan = localStorage.getItem('intendedPlan');
        if (intendedPlan) {
            document.getElementById('schoolPlan').value = intendedPlan;
        }
    } else {
        document.getElementById('hasSchoolState').style.display = 'block';
        // Mock fetching school details
        document.getElementById('displaySchoolName').textContent = "Configured School";
        document.getElementById('displaySchoolCode').textContent = "SCH" + schoolId;
    }
}

async function handleCreateSchool(e) {
    e.preventDefault();
    const session = getSessionData();

    const payload = {
        name: document.getElementById('schoolName').value,
        subdomain: document.getElementById('schoolSubdomain').value,
        plan_id: document.getElementById('schoolPlan').value,
        director_id: session.user_id
    };

    try {
        const response = await fetch(`${API_BASE}/schools/create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        if (data.success) {
            localStorage.setItem('school_id', data.school_id);
            closeModal('createSchoolModal');
            document.getElementById('noSchoolState').style.display = 'none';
            document.getElementById('hasSchoolState').style.display = 'block';
            document.getElementById('displaySchoolName').textContent = payload.name;
            document.getElementById('displaySchoolCode').textContent = data.school_code;
            showAlert('dashboardAlert', 'School created successfully!', 'success');
        } else {
            alert(data.message);
        }
    } catch (err) {
        alert('Failed to create school');
    }
}

// --- Mass Registration ---
let selectedFile = null;
let currentCsvBlob = null;

function updateFileName(input) {
    if (input.files && input.files.length > 0) {
        selectedFile = input.files[0];
        document.getElementById('dropText').innerHTML = `<strong>Selected:</strong> ${selectedFile.name}`;
        document.getElementById('dropzone').style.borderColor = 'var(--primary)';
    }
}

// Dropzone drag/drop support
const dropzone = document.getElementById('dropzone');
if (dropzone) {
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            document.getElementById('csvFile').files = e.dataTransfer.files;
            updateFileName(document.getElementById('csvFile'));
        }
    });
}

async function handleMassRegistration(e) {
    e.preventDefault();

    if (!selectedFile) {
        showAlert('regAlert', 'Please select a CSV file first.', 'error');
        return;
    }

    const schoolId = localStorage.getItem('school_id');
    if (!schoolId || schoolId === "null" || schoolId === "") {
        showAlert('regAlert', 'You must set up your school in the overview before registering users.', 'error');
        return;
    }

    const role = document.getElementById('importRole').value;
    const btn = document.getElementById('uploadBtn');
    btn.disabled = true;
    btn.textContent = 'Processing...';

    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('role', role);
    formData.append('school_id', schoolId);

    try {
        const response = await fetch(`${API_BASE}/users/mass-register`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            showAlert('regAlert', `Successfully generated ${data.count} accounts.`, 'success');
            renderResultsTable(data.csv);

            // Prepare blob for download
            currentCsvBlob = new Blob([data.csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(currentCsvBlob);

            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.onclick = () => {
                const a = document.createElement('a');
                a.href = url;
                a.download = `${role}_credentials_${new Date().getTime()}.csv`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            };
        } else {
            showAlert('regAlert', data.message || 'Error processing file.', 'error');
        }
    } catch (err) {
        showAlert('regAlert', 'Network error.', 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Upload & Process';
    }
}

function renderResultsTable(csvStr) {
    const lines = csvStr.trim().split('\n');
    const tbody = document.querySelector('#resultsTable tbody');
    tbody.innerHTML = '';

    // Skip header (line 0)
    for (let i = 1; i < lines.length; i++) {
        if (!lines[i]) continue;

        // Parse simple CSV line
        const cols = lines[i].match(/(".*?"|[^",\s]+)(?=\s*,|\s*$)/g);
        if (!cols) continue;

        const tr = document.createElement('tr');
        cols.forEach(col => {
            const td = document.createElement('td');
            td.textContent = col.replace(/(^"|"$)/g, ''); // Remove quotes
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    }

    document.getElementById('resultsContainer').style.display = 'block';
}
