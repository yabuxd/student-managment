import { UIUtils, ThemeManager } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
  console.log('EduPortal: Landing page initialized');
  
  // Initialize Core Services
  ThemeManager.init();
  UIUtils.initMobileMenu('mobile-toggle-btn', 'mobile-menu-overlay', 'close-menu-btn');
  
  const signinNav = UIUtils.$('btn-signin-nav');
  const themeToggle = UIUtils.$('theme-toggle-btn');
  
  if (signinNav) {
    signinNav.addEventListener('click', () => {
      window.location.href = '/login.html';
    });
  }

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      ThemeManager.toggle();
    });
  }
});
