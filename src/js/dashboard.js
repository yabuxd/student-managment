import { UIUtils, ThemeManager } from './utils.js';

// Data
const schedule = [
  { time: '09:00', period: 'AM', subject: 'History of Modern Architecture', room: 'Hall A-102', type: 'LECTURE' },
  { time: '11:30', period: 'AM', subject: 'Structural Systems II', room: 'Studio 4', type: 'WORKSHOP' },
  { time: '02:00', period: 'PM', subject: 'Digital Modeling', room: 'Lab 2', type: 'LAB' }
];

const assignments = [
  { title: 'Urban Planning Project', subject: 'Urban Design', dueDate: 'TOMORROW', status: 'URGENT', statusClass: 'tag-urgent' },
  { title: 'Material Science Essay', subject: 'Construction', dueDate: 'FRIDAY', status: 'PENDING', statusClass: 'tag-pending' },
  { title: 'Midterm Portfolio', subject: 'Design Studio', dueDate: 'NEXT WEEK', status: 'IN PROGRESS', statusClass: 'tag-progress' }
];

document.addEventListener('DOMContentLoaded', () => {
  console.log('EduPortal: Dashboard initialized');
  
  ThemeManager.init();
  
  const scheduleList = UIUtils.$('schedule-list');
  const assignmentsList = UIUtils.$('assignments-list');
  const logoutBtn = UIUtils.$('logout-btn');
  const sidebarToggle = UIUtils.$('sidebar-toggle');
  const dashboardContainer = document.querySelector('.dashboard-container');
  const sidebarOverlay = UIUtils.$('sidebar-overlay');

  // Sidebar Toggle Logic
  if (sidebarToggle && dashboardContainer) {
    sidebarToggle.addEventListener('click', () => {
      dashboardContainer.classList.toggle('sidebar-hidden');
    });
  }

  if (sidebarOverlay && dashboardContainer) {
    sidebarOverlay.addEventListener('click', () => {
      dashboardContainer.classList.add('sidebar-hidden');
    });
  }

  function renderDashboard() {
    if (scheduleList) {
      scheduleList.innerHTML = schedule.map(item => `
        <div class="schedule-item">
          <div class="schedule-time">
            <div class="time-val">${item.time}</div>
            <div class="time-ampm">${item.period}</div>
          </div>
          <div class="schedule-details">
            <div class="schedule-name">${item.subject}</div>
            <div class="schedule-meta">
              <span class="meta-loc">
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                ${item.room}
              </span>
              <span class="meta-type">${item.type}</span>
            </div>
          </div>
          <a href="/announcements.html" class="btn-arrow" style="text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </a>
        </div>
      `).join('');
    }

    if (assignmentsList) {
      assignmentsList.innerHTML = assignments.map((item) => `
        <div class="assignment-item">
          <div class="assignment-top">
            <div class="assignment-info">
              <h4>${item.title}</h4>
              <p>${item.subject}</p>
            </div>
            <span class="tag ${item.statusClass}">${item.status}</span>
          </div>
          <div class="assignment-bottom">
            <div class="due-info">
              <span class="due-label">DUE</span>
              <span class="due-val">${item.dueDate}</span>
            </div>
            <a href="/assessment.html" class="btn-submit-action" style="text-decoration: none;">SUBMIT</a>
          </div>
        </div>
      `).join('');
    }
  }

  renderDashboard();

  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      window.location.href = '/index.html';
    });
  }
});
