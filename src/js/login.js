document.addEventListener('DOMContentLoaded', () => {
  const tabStudent = document.getElementById('tab-student');
  const tabTeacher = document.getElementById('tab-teacher');
  const tabIndicator = document.getElementById('tab-indicator');
  const idLabel = document.getElementById('id-label');
  const loginForm = document.getElementById('login-form');

  if (tabStudent && tabTeacher && tabIndicator) {
    tabStudent.addEventListener('click', () => {
      tabStudent.classList.add('active');
      tabStudent.classList.remove('inactive');
      tabTeacher.classList.add('inactive');
      tabTeacher.classList.remove('active');
      tabIndicator.style.left = '0.25rem';
      if (idLabel) idLabel.textContent = 'Student Identity';
    });

    tabTeacher.addEventListener('click', () => {
      tabTeacher.classList.add('active');
      tabTeacher.classList.remove('inactive');
      tabStudent.classList.add('inactive');
      tabStudent.classList.remove('active');
      tabIndicator.style.left = 'calc(50% - 0.25rem)';
      if (idLabel) idLabel.textContent = 'Teacher Identity';
    });
  }

  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      e.preventDefault();
      // In a real app, you'd validate credentials here
      window.location.href = '/dashboard.html';
    });
  }
});
