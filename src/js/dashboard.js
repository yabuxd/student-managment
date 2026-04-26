import { UIUtils, ThemeManager } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
  console.log('SIS Dashboard initialized');
  
  // Theme Manager (Optional for now)
  ThemeManager.init();
  
  const logoutBtn = UIUtils.$('logout-btn');
  const sidebarToggle = UIUtils.$('sidebar-toggle');
  const sidebar = UIUtils.$('sidebar');
  
  // Mobile Sidebar Toggle Logic
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => {
      // Basic toggle for mobile view if needed
      const currentWidth = sidebar.style.width;
      if (currentWidth === '60px' || currentWidth === '') {
        sidebar.style.width = '200px';
      } else {
        sidebar.style.width = '60px';
      }
    });
  }

  // Highlight active navigation based on current URL
  const navBtns = document.querySelectorAll('.sis-nav-btn:not(.logout)');
  const currentPath = window.location.pathname;
  
  navBtns.forEach(btn => {
    if (btn.getAttribute('href') === currentPath || (currentPath === '/' && btn.getAttribute('href') === '/dashboard.html')) {
      navBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    }
  });

  // Dynamic Chart Rendering (Score out of 100)
  function renderPerformanceChart(scores) {
    const container = UIUtils.$('performance-chart-container');
    if (!container) return;

    const width = 400;
    const height = 200;
    const padding = { top: 20, right: 20, bottom: 30, left: 40 };
    const chartWidth = width - padding.left - padding.right;
    const chartHeight = height - padding.top - padding.bottom;

    // Y-axis logic (50 to 100)
    const yMin = 50;
    const yMax = 100;
    
    // Map data to coordinates
    const points = scores.map((score, index) => {
      const x = padding.left + (index * (chartWidth / (scores.length - 1 || 1)));
      const y = padding.top + chartHeight - ((score - yMin) / (yMax - yMin)) * chartHeight;
      return `${x},${y}`;
    }).join(' ');

    const circles = scores.map((score, index) => {
      const x = padding.left + (index * (chartWidth / (scores.length - 1 || 1)));
      const y = padding.top + chartHeight - ((score - yMin) / (yMax - yMin)) * chartHeight;
      return `<circle cx="${x}" cy="${y}" r="4" fill="#60a5fa" />
              <text x="${x}" y="${y - 10}" font-size="10" fill="#64748b" text-anchor="middle" font-weight="bold">${score}%</text>`;
    }).join('');

    // Generate Grid lines & Y-axis labels
    const ySteps = 5;
    let gridLines = '';
    let yLabels = '';
    for (let i = 0; i <= ySteps; i++) {
      const val = yMax - i * ((yMax - yMin) / ySteps);
      const yPos = padding.top + (i * (chartHeight / ySteps));
      gridLines += `<line x1="${padding.left}" y1="${yPos}" x2="${width - padding.right}" y2="${yPos}" stroke="#eaeaea" stroke-width="1" />`;
      yLabels += `<text x="${padding.left - 10}" y="${yPos + 4}" class="sis-chart-label" text-anchor="end">${val}</text>`;
    }

    const svg = `
      <svg viewBox="0 0 ${width} ${height}" class="sis-chart" preserveAspectRatio="xMidYMid meet" style="width: 100%; height: auto;">
        ${gridLines}
        ${yLabels}
        <text x="10" y="${height / 2}" class="sis-chart-label" transform="rotate(-90 10,${height / 2})" text-anchor="middle">Score (%)</text>
        
        <polyline points="${points}" fill="none" stroke="#60a5fa" stroke-width="2" />
        ${circles}

        <!-- Legend -->
        <rect x="${padding.left + 40}" y="${padding.top - 15}" width="60" height="20" fill="white" stroke="#ccc" rx="2" />
        <circle cx="${padding.left + 50}" cy="${padding.top - 5}" r="3" fill="#60a5fa" />
        <line x1="${padding.left + 45}" y1="${padding.top - 5}" x2="${padding.left + 55}" y2="${padding.top - 5}" stroke="#60a5fa" stroke-width="2" />
        <text x="${padding.left + 60}" y="${padding.top - 1}" class="sis-chart-legend">Result</text>
      </svg>
    `;
    container.innerHTML = svg;
  }

  // Simulate fetching dynamic student data
  const studentScores = [68, 75, 82, 88, 94]; // Scores out of 100
  renderPerformanceChart(studentScores);

  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      window.location.href = '/index.html';
    });
  }
});
