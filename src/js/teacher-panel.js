import { UIUtils, ThemeManager } from './utils.js';

// --- Mock Data ---
const MOCK_DATA = {
  students: [
    { id: 'S101', name: 'Alex Johnson', class: 'Grade 10 A', attendance: '98%', performance: 'A', status: 'Active' },
    { id: 'S102', name: 'Maria Garcia', class: 'Grade 10 A', attendance: '95%', performance: 'B+', status: 'Active' },
    { id: 'S103', name: 'James Wilson', class: 'Grade 9 B', attendance: '88%', performance: 'C', status: 'Warning' },
    { id: 'S104', name: 'Chloe Chen', class: 'Grade 10 A', attendance: '100%', performance: 'A+', status: 'Active' },
    { id: 'S105', name: 'Ryan Park', class: 'Grade 9 B', attendance: '92%', performance: 'B', status: 'Active' }
  ],
  classes: [
    { id: 'C1', name: 'Grade 10 A', subject: 'Advanced Mathematics', students: 24, room: 'Room 302' },
    { id: 'C2', name: 'Grade 9 B', subject: 'Algebra I', students: 18, room: 'Room 105' },
    { id: 'C3', name: 'Grade 11 C', subject: 'Calculus BC', students: 15, room: 'Lab 04' }
  ],
  announcements: [
    { id: 1, title: 'Midterm Exam Schedule', target: 'All Classes', date: '2026-05-10', content: 'The midterm exams will begin next monday. Please review the syllabus.' },
    { id: 2, title: 'Field Trip to Science Museum', target: 'Grade 10 A', date: '2026-05-15', content: 'Join us for a field trip to the city science museum. Consent forms required.' }
  ]
};

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('tp-sidebar');
  const mobileToggle = document.getElementById('tp-mobile-toggle');
  const navLinks = document.querySelectorAll('.tp-nav__link');
  const viewContainer = document.getElementById('tp-view-container');
  const viewTitle = document.getElementById('tp-view-title');
  const themeSidebarToggle = document.getElementById('tp-theme-toggle');
  const themeHeaderToggle = document.getElementById('tp-header-theme-toggle');
  const modalOverlay = document.getElementById('tp-modal-overlay');

  // --- Theme Logic ---
  ThemeManager.init();

  [themeSidebarToggle, themeHeaderToggle].forEach(btn => {
    btn?.addEventListener('click', () => {
      ThemeManager.toggle();
    });
  });

  // --- Sidebar Mobile Logic ---
  mobileToggle.addEventListener('click', () => {
    sidebar.classList.toggle('tp-sidebar--open');
  });

  // Close sidebar on link click (mobile)
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      sidebar.classList.remove('tp-sidebar--open');
    });
  });

  // --- View Switcher ---
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const view = link.getAttribute('data-view');
      if (!view) return;

      // Update active state
      navLinks.forEach(l => l.classList.remove('active'));
      link.classList.add('active');

      // Update URL hash without jumping
      history.pushState(null, null, `#${view}`);
      
      renderView(view);
    });
  });

  function renderView(viewId) {
    const title = viewId.charAt(0).toUpperCase() + viewId.slice(1);
    viewTitle.textContent = title;

    switch(viewId) {
      case 'dashboard':
        renderDashboard();
        break;
      case 'students':
        renderStudents();
        break;
      case 'classes':
        renderClasses();
        break;
      case 'assessments':
        renderAssessments();
        break;
      case 'grades':
        renderGrades();
        break;
      case 'announcements':
        renderAnnouncements();
        break;
      case 'messages':
        renderMessages();
        break;
      case 'settings':
        renderSettings();
        break;
      default:
        renderDashboard();
    }
  }

  // --- View Renders ---

  function renderDashboard() {
    viewContainer.innerHTML = `
      <div class="tp-stats-grid">
        <div class="tp-stat-card">
          <div class="tp-stat-icon" style="background: #e0f2fe; color: #0ea5e9;">👥</div>
          <div>
            <div class="tp-stat-value">57</div>
            <div class="tp-stat-label">Total Students</div>
          </div>
        </div>
        <div class="tp-stat-card">
          <div class="tp-stat-icon" style="background: #fef3c7; color: #d97706;">📚</div>
          <div>
            <div class="tp-stat-value">4</div>
            <div class="tp-stat-label">Active Classes</div>
          </div>
        </div>
        <div class="tp-stat-card">
          <div class="tp-stat-icon" style="background: #fdf2f8; color: #db2777;">⏳</div>
          <div>
            <div class="tp-stat-value">12</div>
            <div class="tp-stat-label">Pending Graded</div>
          </div>
        </div>
      </div>

      <div class="tp-card">
        <div class="tp-card-header">
          <h3 class="tp-card-title">Recent Activity</h3>
          <button class="tp-btn tp-btn--outline" onclick="alert('Viewing all logs...')">View All</button>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 1rem; background: var(--tp-bg);">
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #dcfce7; display: flex; align-items: center; justify-content: center;">📝</div>
            <div>
              <p style="font-weight: 700; font-size: 0.875rem;">Created Assessment: Calculus Quiz 2</p>
              <p style="font-size: 0.75rem; color: var(--tp-text-muted);">2 hours ago • Grade 11 C</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 1rem;">
            <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; background: #e0f2fe; display: flex; align-items: center; justify-content: center;">💬</div>
            <div>
              <p style="font-weight: 700; font-size: 0.875rem;">New message from Parent (Ryan Park)</p>
              <p style="font-size: 0.75rem; color: var(--tp-text-muted);">5 hours ago</p>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function renderStudents() {
    viewContainer.innerHTML = `
      <div class="tp-card">
        <div class="tp-card-header">
          <div style="display: flex; gap: 1rem; flex: 1;">
            <input type="text" placeholder="Search students..." class="tp-input" style="max-width: 300px;">
            <select class="tp-select" style="max-width: 150px;">
              <option>All Classes</option>
              <option>Grade 10 A</option>
              <option>Grade 9 B</option>
            </select>
          </div>
          <button class="tp-btn tp-btn--primary">Export CSV</button>
        </div>
        <div class="tp-table-wrap">
          <table class="tp-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Attendance</th>
                <th>Grade</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              ${MOCK_DATA.students.map(s => `
                <tr>
                  <td style="font-weight: 700;">${s.id}</td>
                  <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                      <div style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">${s.name.charAt(0)}</div>
                      ${s.name}
                    </div>
                  </td>
                  <td>${s.class}</td>
                  <td>${s.attendance}</td>
                  <td style="font-weight: 800; color: var(--tp-primary);">${s.performance}</td>
                  <td><span class="tp-badge tp-badge--${s.status === 'Active' ? 'success' : 'warning'}">${s.status}</span></td>
                  <td>
                    <button class="tp-btn tp-btn--outline" style="padding: 0.5rem;" onclick="tp_handleStudentProfile('${s.name}')">View</button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  function renderClasses() {
    viewContainer.innerHTML = `
      <div class="tp-stats-grid">
        ${MOCK_DATA.classes.map(c => `
          <div class="tp-card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
               <div style="width: 2.5rem; height: 2.5rem; background: var(--tp-accent-light); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">🏫</div>
               <span class="tp-badge tp-badge--success">Online</span>
            </div>
            <h4 style="margin-bottom: 0.25rem;">${c.name}</h4>
            <p style="font-size: 0.8125rem; color: var(--tp-text-muted); margin-bottom: 1.25rem;">${c.subject}</p>
            <div style="display: flex; justify-content: space-between; font-size: 0.875rem; font-weight: 700; border-top: 1px solid var(--tp-border); padding-top: 1rem;">
              <span>${c.students} Students</span>
              <span style="color: var(--tp-primary);">${c.room}</span>
            </div>
            <button class="tp-btn tp-btn--primary" style="width: 100%; margin-top: 1.5rem;" onclick="alert('Opening roster for ${c.name}...')">Manage Class</button>
          </div>
        `).join('')}
      </div>
    `;
  }

  function renderAssessments() {
    viewContainer.innerHTML = `
      <div class="tp-card">
        <h3 class="tp-card-title" style="margin-bottom: 1.5rem;">Create New Assessment</h3>
        <form onsubmit="event.preventDefault(); alert('Assessment Created Successfully!'); this.reset();">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="tp-form-group">
              <label class="tp-label">Assessment Title</label>
              <input type="text" class="tp-input" placeholder="e.g., Algebra Quiz 1" required>
            </div>
            <div class="tp-form-group">
               <label class="tp-label">Target Class</label>
               <select class="tp-select">
                 <option>Grade 10 A</option>
                 <option>Grade 9 B</option>
                 <option>Grade 11 C</option>
               </select>
            </div>
          </div>
          <div class="tp-form-group">
            <label class="tp-label">Instructions</label>
            <textarea class="tp-textarea" placeholder="Enter instructions for students..."></textarea>
          </div>
          <button type="submit" class="tp-btn tp-btn--primary">Launch Assessment</button>
        </form>
      </div>

      <div class="tp-card">
        <h3 class="tp-card-title" style="margin-bottom: 1.5rem;">Recent Assessments</h3>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
          <div style="padding: 1.25rem; border: 1px solid var(--tp-border); border-radius: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
              <p style="font-weight: 700;">Calculus Midterm Review</p>
              <p style="font-size: 0.75rem; color: var(--tp-text-muted);">Grade 11 C • 15 Submissions • Due May 12</p>
            </div>
            <button class="tp-btn tp-btn--outline" onclick="alert('Viewing submissions...')">View Submissions</button>
          </div>
          <div style="padding: 1.25rem; border: 1px solid var(--tp-border); border-radius: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
              <p style="font-weight: 700;">Vector Space Practice</p>
              <p style="font-size: 0.75rem; color: var(--tp-text-muted);">Grade 10 A • 0 Submissions • Due May 15</p>
            </div>
            <span class="tp-badge tp-badge--warning">Draft</span>
          </div>
        </div>
      </div>
    `;
  }

  function renderGrades() {
    viewContainer.innerHTML = `
      <div class="tp-card">
        <div class="tp-card-header">
           <h3 class="tp-card-title">Gradebook: Grade 10 A</h3>
           <div style="display: flex; gap: 1rem;">
              <button class="tp-btn tp-btn--outline" onclick="alert('PDF Generated')">PDF Report</button>
              <button class="tp-btn tp-btn--primary">Save Changes</button>
           </div>
        </div>
        <div class="tp-table-wrap">
          <table class="tp-table">
            <thead>
              <tr>
                <th>Student</th>
                <th>Quiz 1</th>
                <th>Quiz 2</th>
                <th>Midterm</th>
                <th>Avg.</th>
                <th>Final Grade</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="font-weight: 700;">Alex Johnson</td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="92"></td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="88"></td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="95"></td>
                <td style="font-weight: 800;">91.6%</td>
                <td><span class="tp-badge tp-badge--success">A</span></td>
              </tr>
              <tr>
                <td style="font-weight: 700;">Maria Garcia</td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="85"></td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="90"></td>
                <td><input type="number" class="tp-input" style="width: 80px;" value="82"></td>
                <td style="font-weight: 800;">85.6%</td>
                <td><span class="tp-badge tp-badge--success">B+</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    `;
  }

  function renderAnnouncements() {
    viewContainer.innerHTML = `
      <div class="tp-card">
        <h3 class="tp-card-title" style="margin-bottom: 1.5rem;">New Announcement</h3>
        <form onsubmit="event.preventDefault(); alert('Announcement Posted!'); this.reset();">
          <div class="tp-form-group">
            <label class="tp-label">Title</label>
            <input type="text" class="tp-input" placeholder="e.g., Lab Session Rescheduled" required>
          </div>
          <div class="tp-form-group">
            <label class="tp-label">Post to</label>
            <select class="tp-select">
              <option>All Classes</option>
              <option>Grade 10 A</option>
              <option>Grade 9 B</option>
            </select>
          </div>
          <div class="tp-form-group">
            <label class="tp-label">Message</label>
            <textarea class="tp-textarea" style="min-height: 120px;" placeholder="Write your message here..."></textarea>
          </div>
          <button type="submit" class="tp-btn tp-btn--primary">Post to Portal</button>
        </form>
      </div>

      <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        ${MOCK_DATA.announcements.map(a => `
          <div class="tp-card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
              <span class="tp-badge tp-badge--warning" style="background: var(--tp-accent-light);">${a.target}</span>
              <span style="font-size: 0.75rem; color: var(--tp-text-muted); font-weight: 700;">${a.date}</span>
            </div>
            <h4 style="margin-bottom: 0.75rem;">${a.title}</h4>
            <p style="font-size: 0.875rem; color: var(--tp-text-muted); line-height: 1.6;">${a.content}</p>
          </div>
        `).join('')}
      </div>
    `;
  }

  function renderMessages() {
    viewContainer.innerHTML = `
      <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; height: 700px;">
        <!-- Sidebar -->
        <div class="tp-card" style="padding: 0; display: flex; flex-direction: column; overflow: hidden;">
          <div style="padding: 1.5rem; border-bottom: 1px solid var(--tp-border);">
            <h3 style="font-size: 1.125rem; margin-bottom: 1rem;">Messages</h3>
            <div style="display: flex; gap: 0.5rem; background: var(--tp-bg); padding: 0.25rem; border-radius: 0.75rem;">
               <button class="tp-btn tp-btn--outline" style="flex: 1; padding: 0.5rem; font-size: 0.75rem; border: none; background: var(--tp-card-bg); box-shadow: var(--tp-shadow);">Individuals</button>
               <button class="tp-btn tp-btn--outline" style="flex: 1; padding: 0.5rem; font-size: 0.75rem; border: none; background: transparent;">Groups</button>
            </div>
          </div>
          
          <div style="flex: 1; overflow-y: auto; padding: 1rem;">
             <p style="font-size: 0.65rem; font-weight: 800; color: var(--tp-text-muted); text-transform: uppercase; margin-bottom: 0.75rem; padding-left: 0.5rem;">Recent Conversations</p>
             <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="padding: 1rem; border-radius: 1rem; background: var(--tp-accent-light); border: 1px solid var(--tp-primary); cursor: pointer;">
                   <div style="display: flex; gap: 0.75rem; align-items: center;">
                      <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">RP</div>
                      <div>
                         <p style="font-weight: 700; font-size: 0.875rem;">Ryan Park</p>
                         <p style="font-size: 0.75rem; color: var(--tp-text-muted);">"Thank you for the..."</p>
                      </div>
                   </div>
                </div>
                <div style="padding: 1rem; border-radius: 1rem; cursor: pointer;">
                   <div style="display: flex; gap: 0.75rem; align-items: center;">
                      <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #fef3c7; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">EW</div>
                      <div>
                         <p style="font-weight: 700; font-size: 0.875rem;">Emily Wilson</p>
                         <p style="font-size: 0.75rem; color: var(--tp-text-muted);">"When is the next..."</p>
                      </div>
                   </div>
                </div>
             </div>

             <p style="font-size: 0.65rem; font-weight: 800; color: var(--tp-text-muted); text-transform: uppercase; margin: 1.5rem 0 0.75rem; padding-left: 0.5rem;">Your Classes</p>
             <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="padding: 1rem; border-radius: 1rem; cursor: pointer;">
                   <div style="display: flex; gap: 0.75rem; align-items: center;">
                      <div style="width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; background: #dcfce7; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem;">10B</div>
                      <div>
                         <p style="font-weight: 700; font-size: 0.875rem;">Grade 10 - Section B</p>
                         <p style="font-size: 0.75rem; color: var(--tp-text-muted);">24 Members Online</p>
                      </div>
                   </div>
                </div>
             </div>
          </div>
        </div>

        <!-- Chat Area -->
        <div class="tp-card" style="padding: 0; display: flex; flex-direction: column; overflow: hidden;">
          <div style="padding: 1.5rem; border-bottom: 1px solid var(--tp-border); display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
               <div style="width: 3rem; height: 3rem; border-radius: 1rem; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem;">RP</div>
               <div>
                  <p style="font-weight: 700; font-size: 1rem;">Ryan Park (Father of J. Park)</p>
                  <div style="display: flex; align-items: center; gap: 0.35rem; color: #22c55e;">
                     <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                     <span style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">End-to-end Encrypted</span>
                  </div>
               </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
               <button class="tp-btn tp-btn--outline tp-btn--icon">
                 <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
               </button>
               <button class="tp-btn tp-btn--outline tp-btn--icon">
                 <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
               </button>
            </div>
          </div>

          <div class="tp-chat-box" style="flex: 1; background: var(--tp-bg); display: flex; flex-direction: column;">
             <div class="tp-chat-messages" style="flex: 1; padding: 2rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="tp-chat-bubble tp-chat-bubble--parent">Hello Dr. Smith, I saw the recent grades for Ryan. How can we help him improve in Geometry?</div>
                <div class="tp-chat-bubble tp-chat-bubble--teacher">Hello Ryan! He is doing well, but needs more practice with proofs. I recommend the extra worksheets on his portal.</div>
                <div class="tp-chat-bubble tp-chat-bubble--parent">Thank you for the update. We will work on those this weekend.</div>
             </div>
             <form class="tp-chat-input-wrap" style="padding: 1.5rem; background: var(--tp-card-bg); border-top: 1px solid var(--tp-border); display: flex; gap: 1rem;" onsubmit="event.preventDefault(); alert('Message sent!'); this.reset();">
                <input type="text" class="tp-input" placeholder="Type a message to Ryan..." style="flex: 1;">
                <button type="submit" class="tp-btn tp-btn--primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polyline points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
             </form>
          </div>
        </div>
      </div>
    `;
  }

  function renderSettings() {
    viewContainer.innerHTML = `
      <div style="max-width: 600px;">
        <div class="tp-card">
          <h3 class="tp-card-title" style="margin-bottom: 2rem;">Profile Information</h3>
          <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=200" style="width: 100px; height: 100px; border-radius: 2rem; border: 4px solid var(--tp-accent-light);">
            <button class="tp-btn tp-btn--outline">Change Photo</button>
          </div>
          <form onsubmit="event.preventDefault(); alert('Profile Updated');">
            <div class="tp-form-group">
              <label class="tp-label">Full Name</label>
              <input type="text" class="tp-input" value="Dr. Sarah Smith">
            </div>
            <div class="tp-form-group">
              <label class="tp-label">Email Address</label>
              <input type="email" class="tp-input" value="s.smith@eduportal.com">
            </div>
            <button type="submit" class="tp-btn tp-btn--primary">Update Profile</button>
          </form>
        </div>

        <div class="tp-card">
          <h3 class="tp-card-title" style="margin-bottom: 2rem;">Notification Settings</h3>
          <form onsubmit="event.preventDefault(); alert('Notification settings saved');">
            <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2rem;">
              
              <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--tp-border);">
                <div>
                  <p style="font-weight: 700; font-size: 0.9375rem;">Announcements</p>
                  <p style="font-size: 0.75rem; color: var(--tp-text-muted);">Notify when school-wide or class announcements are posted.</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox" checked> Email
                  </label>
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox" checked> In-app
                  </label>
                </div>
              </div>

              <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--tp-border);">
                <div>
                  <p style="font-weight: 700; font-size: 0.9375rem;">Direct Messages</p>
                  <p style="font-size: 0.75rem; color: var(--tp-text-muted);">Receive notifications for new messages from parents or staff.</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox" checked> Email
                  </label>
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox" checked> In-app
                  </label>
                </div>
              </div>

              <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid var(--tp-border);">
                <div>
                  <p style="font-weight: 700; font-size: 0.9375rem;">Assessment Submissions</p>
                  <p style="font-size: 0.75rem; color: var(--tp-text-muted);">Alert me when students submit assignments or quizzes.</p>
                </div>
                <div style="display: flex; gap: 1rem;">
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox"> Email
                  </label>
                  <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; font-weight: 600;">
                    <input type="checkbox" checked> In-app
                  </label>
                </div>
              </div>

            </div>
            <button type="submit" class="tp-btn tp-btn--primary">Save Preferences</button>
          </form>
        </div>

        <div class="tp-card">
          <h3 class="tp-card-title" style="margin-bottom: 2rem;">Security</h3>
          <form onsubmit="event.preventDefault(); alert('Password Changed'); this.reset();">
            <div class="tp-form-group">
              <label class="tp-label">Current Password</label>
              <input type="password" class="tp-input" placeholder="••••••••">
            </div>
            <div class="tp-form-group">
              <label class="tp-label">New Password</label>
              <input type="password" class="tp-input" placeholder="Minimum 8 characters">
            </div>
            <button type="submit" class="tp-btn tp-btn--outline">Reset Password</button>
          </form>
        </div>
      </div>
    `;
  }

  // Initial View
  const currentHash = window.location.hash.replace('#', '') || 'dashboard';
  navLinks.forEach(l => {
    if (l.getAttribute('data-view') === currentHash) l.classList.add('active');
  });
  renderView(currentHash);
});

// Global functions for inline EventListeners
window.tp_handleStudentProfile = (name) => {
  const modalOverlay = document.getElementById('tp-modal-overlay');
  const modal = document.getElementById('tp-modal');

  modal.innerHTML = `
    <div style="text-align: center;">
      <div style="width: 80px; height: 80px; border-radius: 2rem; background: var(--tp-accent-light); margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👤</div>
      <h2 style="margin-bottom: 0.5rem;">${name}</h2>
      <p style="color: var(--tp-text-muted); margin-bottom: 2rem;">Student at EduPortal since 2024</p>
      
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; text-align: left;">
        <div style="padding: 1rem; background: var(--tp-bg); border-radius: 1rem;">
          <p style="font-size: 0.65rem; color: var(--tp-text-muted); font-weight: 800; text-transform: uppercase;">GPA</p>
          <p style="font-weight: 800; font-size: 1.125rem;">3.84</p>
        </div>
        <div style="padding: 1rem; background: var(--tp-bg); border-radius: 1rem;">
          <p style="font-size: 0.65rem; color: var(--tp-text-muted); font-weight: 800; text-transform: uppercase;">Absences</p>
          <p style="font-weight: 800; font-size: 1.125rem;">2 Days</p>
        </div>
      </div>

      <div style="display: flex; gap: 1rem;">
        <button class="tp-btn tp-btn--primary" style="flex: 1;" onclick="alert('Message sent to parents')">Contact Parent</button>
        <button class="tp-btn tp-btn--outline" style="flex: 1;" onclick="document.getElementById('tp-modal-overlay').classList.remove('active')">Close</button>
      </div>
    </div>
  `;
  modalOverlay.classList.add('active');
};
