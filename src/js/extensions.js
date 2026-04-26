/* 
  Extensions Logic 
  Interactive features for EduPortal Extensions
*/

import { UIUtils, ThemeManager } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
  console.log('EduPortal: Extensions Initialized');

  ThemeManager.init();

  // 1. STUDENT REGISTRATION LOGIC
  const genBtn = UIUtils.$('ep-reg-gen-btn');
  const idInput = UIUtils.$('ep-reg-id-input');
  const regForm = UIUtils.$('ep-reg-form');

  if (genBtn && idInput) {
    genBtn.addEventListener('click', () => {
      const year = new Date().getFullYear();
      const random = Math.floor(1000 + Math.random() * 9000);
      idInput.value = `STU-${year}-${random}`;
      
      // Visual feedback
      idInput.style.borderColor = 'var(--accent-color)';
      setTimeout(() => idInput.style.borderColor = '', 1000);
    });
  }

  if (regForm) {
    regForm.addEventListener('submit', (e) => {
      e.preventDefault();
      alert('Registration successful! Redirecting to login...');
      window.location.href = '/login.html';
    });
  }

  // 2. PARENTAL PANEL GRAPHS
  const bars = document.querySelectorAll('.ep-parent-bar');
  if (bars.length > 0) {
    // Simulate entry animation
    setTimeout(() => {
      bars.forEach(bar => {
        const targetHeight = bar.getAttribute('data-height');
        bar.style.height = targetHeight + '%';
      });
    }, 300);
  }

  // 3. ANNOUNCEMENTS
  const newsForm = document.getElementById('ep-feed-form');
  if (newsForm) {
    newsForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = document.getElementById('news-title').value;
      const content = document.getElementById('news-content').value;
      alert(`Announcement Pending Approval: ${title}`);
      newsForm.reset();
    });
  }

  // 5. ASSESSMENT QUIZ
  const quizOptions = document.querySelectorAll('.ep-quiz-option');
  const quizSubmit = document.getElementById('ep-quiz-submit');
  
  if (quizOptions.length > 0) {
    quizOptions.forEach(option => {
      option.addEventListener('click', function() {
        // Clear others in group
        const group = this.closest('.ep-reg-group');
        group.querySelectorAll('.ep-quiz-option').forEach(opt => {
          opt.classList.remove('ep-quiz-option--selected');
        });
        this.classList.add('ep-quiz-option--selected');
      });
    });
  }

  if (quizSubmit) {
    quizSubmit.addEventListener('click', () => {
      const selected = document.querySelectorAll('.ep-quiz-option--selected');
      if (selected.length < 2) {
        alert('Please answer all questions before submitting.');
        return;
      }
      
      // Mock result modal
      const score = Math.floor(Math.random() * 20) + 80;
      alert(`Quiz Submitted Successfully!\nYour Score: ${score}/100\n\nResults have been sent to your teacher.`);
    });
  }

  // 6. GRADE EXPORT
  const exportCsv = document.getElementById('ep-export-csv');
  const exportPdf = document.getElementById('ep-export-pdf');

  if (exportCsv) {
    exportCsv.addEventListener('click', () => {
      alert('Preparing CSV Export...\nDownloading: student_grades_2024.csv');
    });
  }
  if (exportPdf) {
    exportPdf.addEventListener('click', () => {
      alert('Generating PDF Report...\nDownloading: academic_report_alex.pdf');
    });
  }

  // 7. PARENT PANEL MESSAGE FORM
  const parentMsgForm = document.getElementById('parent-msg-form');
  if (parentMsgForm) {
    parentMsgForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const textarea = parentMsgForm.querySelector('textarea');
      if (textarea && textarea.value.trim()) {
        alert(`Message sent to Dr. Smith:\n"${textarea.value}"\n\nYou will be notified when the teacher replies.`);
        textarea.value = '';
      } else {
        alert('Please enter a message before sending.');
      }
    });
  }
});
