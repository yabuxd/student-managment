/**
 * EduPortal Core Utilities
 * Standard helpers for modular JS architecture
 */

export const ThemeManager = {
  key: 'eduportal-theme',
  init() {
    const saved = localStorage.getItem(this.key) || 'light';
    this.apply(saved);
  },
  apply(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    const bodyClass = theme === 'dark' ? 'tp-theme-dark' : 'tp-theme-light';
    document.body.classList.remove('tp-theme-light', 'tp-theme-dark');
    document.body.classList.add(bodyClass);
    localStorage.setItem(this.key, theme);
  },
  toggle() {
    const current = localStorage.getItem(this.key) === 'dark' ? 'light' : 'dark';
    this.apply(current);
  }
};

export const UIUtils = {
  // Safe element selector
  $(id) { return document.getElementById(id); },
  
  // Mobile Menu Handler
  initMobileMenu(toggleId, menuId, closeId) {
    const toggle = this.$(toggleId);
    const menu = this.$(menuId);
    const close = this.$(closeId);
    
    if (toggle && menu) {
      toggle.addEventListener('click', () => {
        menu.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    }
    
    if (close && menu) {
      close.addEventListener('click', () => {
        menu.classList.remove('active');
        document.body.style.overflow = '';
      });
    }
    
    // Close on link click
    menu?.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.remove('active');
        document.body.style.overflow = '';
      });
    });
  }
};
