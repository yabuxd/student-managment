// Data
const schedule = [
  { time: "09:00 AM", subject: "History of Modern Architecture", room: "Hall A-102", type: "Lecture" },
  { time: "11:30 AM", subject: "Structural Systems II", room: "Studio 4", type: "Workshop" },
  { time: "02:00 PM", subject: "Digital Modeling", room: "Lab 2", type: "Lab" }
];

const assignments = [
  { title: "Urban Planning Project", subject: "Urban Design", due: "Tomorrow", status: "Urgent", statusClass: "status-urgent" },
  { title: "Material Science Essay", subject: "Construction", due: "Friday", status: "Pending", statusClass: "status-pending" },
  { title: "Midterm Portfolio", subject: "Design Studio", due: "Next Monday", status: "In Progress", statusClass: "status-progress" }
];

// State
let currentRole = 'student';

// DOM Elements
const landingPage = document.getElementById('landing-page');
const loginPage = document.getElementById('login-page');
const dashboardPage = document.getElementById('dashboard-page');
const navLoginBtn = document.getElementById('nav-login-btn');
const heroGetStarted = document.getElementById('hero-get-started');
const tabStudent = document.getElementById('tab-student');
const tabTeacher = document.getElementById('tab-teacher');
const tabIndicator = document.getElementById('tab-indicator');
const idLabel = document.getElementById('id-label');
const idInput = document.getElementById('idNumber');
const loginForm = document.getElementById('login-form');
const logoutBtn = document.getElementById('logout-btn');
const scheduleList = document.getElementById('schedule-list');
const assignmentsList = document.getElementById('assignments-list');

// Navigation Logic
function showLogin() {
  console.log('Navigating to Login Page');
  landingPage.classList.add('hidden');
  loginPage.classList.remove('hidden');
  dashboardPage.classList.add('hidden');
  document.body.classList.add('overflow-hidden');
  
  // Force layout recalculation
  loginPage.offsetHeight; 
}

function showLanding() {
  console.log('Navigating to Landing Page');
  landingPage.classList.remove('hidden');
  loginPage.classList.add('hidden');
  dashboardPage.classList.add('hidden');
  document.body.classList.remove('overflow-hidden');
}

navLoginBtn.addEventListener('click', (e) => {
  e.preventDefault();
  showLogin();
});

heroGetStarted.addEventListener('click', (e) => {
  e.preventDefault();
  showLogin();
});

// Tab Switching
tabStudent.addEventListener('click', () => {
  currentRole = 'student';
  tabIndicator.style.transform = 'translateX(0)';
  tabStudent.classList.add('tab-active');
  tabStudent.classList.remove('tab-inactive');
  tabTeacher.classList.add('tab-inactive');
  tabTeacher.classList.remove('tab-active');
  idLabel.textContent = 'Student Identity';
  idInput.placeholder = 'ID Number';
});

tabTeacher.addEventListener('click', () => {
  currentRole = 'teacher';
  tabIndicator.style.transform = 'translateX(100%)';
  tabTeacher.classList.add('tab-active');
  tabTeacher.classList.remove('tab-inactive');
  tabStudent.classList.add('tab-inactive');
  tabStudent.classList.remove('tab-active');
  idLabel.textContent = 'Faculty Identity';
  idInput.placeholder = 'Employee ID';
});

// Login Logic
loginForm.addEventListener('submit', (e) => {
  e.preventDefault();
  
  if (currentRole === 'student') {
    loginPage.classList.add('hidden');
    dashboardPage.classList.remove('hidden');
    renderDashboard();
  } else {
    alert("Teacher dashboard is coming soon!");
  }
});

// Logout Logic
logoutBtn.addEventListener('click', () => {
  showLanding();
  loginForm.reset();
});

// Render Dashboard Data
function renderDashboard() {
  // Render Schedule
  scheduleList.innerHTML = schedule.map(item => `
    <div class="schedule-item">
      <div class="schedule-time">
        <p class="time-main">${item.time.split(' ')[0]}</p>
        <p class="time-period">${item.time.split(' ')[1]}</p>
      </div>
      <div class="schedule-divider"></div>
      <div class="schedule-info">
        <p class="schedule-subject">${item.subject}</p>
        <div class="schedule-meta">
          <span class="meta-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            ${item.room}
          </span>
          <span class="type-badge">
            ${item.type}
          </span>
        </div>
      </div>
      <button class="schedule-action">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
      </button>
    </div>
  `).join('');

  // Render Assignments
  assignmentsList.innerHTML = assignments.map((task, index) => `
    <div class="assignment-item">
      <div class="assignment-header">
        <div>
          <p class="assignment-title">${task.title}</p>
          <p class="assignment-subject">${task.subject}</p>
        </div>
        <span class="status-badge ${task.statusClass}">
          ${task.status}
        </span>
      </div>
      <div class="assignment-footer">
        <span class="due-date">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Due ${task.due}
        </span>
        <button class="btn-submit">Submit</button>
      </div>
      ${index !== assignments.length - 1 ? '<div class="assignment-divider"></div>' : ''}
    </div>
  `).join('');
}
