document.addEventListener('DOMContentLoaded', () => {
  console.log('Landing page loaded');
  
  const signinNav = document.getElementById('btn-signin-nav');
  const signinHero = document.getElementById('btn-signin-hero');
  
  if (signinNav) {
    signinNav.addEventListener('click', () => {
      window.location.href = '/login.html';
    });
  }
  
  if (signinHero) {
    signinHero.addEventListener('click', () => {
      window.location.href = '/login.html';
    });
  }
});
