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
            console.log(data);
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

// --- Dashboard Vercel Flow ---
function toggleSidebar() {
    const sidebar = document.getElementById('mainSidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

function switchMainView(viewId, sidebarItem) {
    document.querySelectorAll('.view-container').forEach(v => v.classList.remove('active'));
    document.getElementById(viewId).classList.add('active');

    if (sidebarItem) {
        document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
        sidebarItem.classList.add('active');
    }
}

function openCreateSchoolView() {
    switchMainView('createSchoolView', null);
}

function selectTemplate(templateName, element) {
    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
    element.classList.add('selected');
    document.getElementById('selectedTemplate').value = templateName;
}

async function loadProjects() {
    const session = getSessionData();
    if (!session || !session.user_id) return;

    try {
        const response = await fetch(`${API_BASE}/schools/list?director_id=${session.user_id}`);
        const data = await response.json();

        if (data.success) {
            const grid = document.getElementById('schoolsGrid');
            const noSchoolState = document.getElementById('noSchoolState');

            // Update UI with Plan Info
            if (data.plan) {
                const planDisplay = document.getElementById('currentPlanDisplay');
                if (planDisplay) planDisplay.textContent = `Current Plan: ${data.plan.name} (${data.plan.max_schools} Schools Max)`;
            }

            if (!data.schools || data.schools.length === 0) {
                noSchoolState.style.display = 'block';
                grid.style.display = 'none';

                const intendedPlan = localStorage.getItem('intendedPlan');
                if (intendedPlan) {
                    document.getElementById('schoolPlan').value = intendedPlan;
                }
            } else {
                noSchoolState.style.display = 'none';
                grid.style.display = 'grid';
                grid.innerHTML = ''; // Clear grid

                data.schools.forEach(school => {
                    const card = document.createElement('div');
                    card.className = 'project-card';
                    card.onclick = () => {
                        localStorage.setItem('school_subdomain', school.subdomain);
                        openSchoolManage(school.name, school.school_code, school.id);
                    };
                    card.innerHTML = `
                        <div class="project-card-header">
                            <div class="project-card-icon">${school.name.charAt(0).toUpperCase()}</div>
                            <div class="project-card-title">${school.name}</div>
                        </div>
                        <div class="project-card-desc">${school.subdomain}.edumanage.com</div>
                    `;
                    grid.appendChild(card);
                });
            }
        }
    } catch (err) {
        console.error("Failed to load schools:", err);
    }
}

function loadDashboardData() {
    const session = getSessionData();
    if (session) {
        document.getElementById('navDirectorName').textContent = session.full_name || 'Director Account';
        document.getElementById('navAvatar').textContent = session.full_name ? session.full_name.charAt(0) : 'D';
    }

    loadProjects();
}

function openSchoolManage(name, code, id) {
    document.getElementById('projectsView').classList.remove('active');
    document.getElementById('schoolManageView').classList.add('active');

    document.getElementById('manageSchoolName').textContent = name;
    document.getElementById('manageSchoolCode').textContent = code;

    const subdomain = localStorage.getItem('school_subdomain') || 'testschool';
    const visitBtn = document.getElementById('visitSiteBtn');
    if (visitBtn) {
        visitBtn.href = `../sites/${subdomain}/index.php`;
    }
}

function goBackToProjects() {
    document.getElementById('schoolManageView').classList.remove('active');
    document.getElementById('projectsView').classList.add('active');
}

async function handleCreateSchool(e) {
    e.preventDefault();
    const session = getSessionData();

    const payload = {
        name: document.getElementById('schoolName').value,
        subdomain: document.getElementById('schoolSubdomain').value,
        plan_id: document.getElementById('schoolPlan').value,
        director_id: session.user_id,
        template: document.getElementById('selectedTemplate').value
    };

    try {
        const response = await fetch(`${API_BASE}/schools/create`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();
        // const data = await response.text();
        if (data.success) {
            localStorage.setItem('school_id', data.school_id);
            localStorage.setItem('school_name', payload.name);
            localStorage.setItem('school_subdomain', payload.subdomain);
            localStorage.setItem('school_code', data.school_code);
            localStorage.setItem('school_template', payload.template); // Mock saving template choice

            // Go to the new school manage view
            openSchoolManage(payload.name, data.school_code, data.school_id);
        } else {
            console.log(data);
            alert(data.message);
        }
    } catch (err) {
        console.log(err);
        // alert('Failed to create school');
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
