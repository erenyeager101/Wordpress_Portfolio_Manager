/**
 * Theme Toggle - Dark Mode Functionality
 * Handles theme switching with localStorage persistence
 */

(function() {
  'use strict';

  // Remove no-js class
  document.documentElement.classList.remove('no-js');

  const STORAGE_KEY = 'smart-portfolio-theme';
  const THEME_ATTR = 'data-theme';
  
  // Get saved theme or default to light
  const getSavedTheme = () => {
    return localStorage.getItem(STORAGE_KEY) || 'light';
  };
  
  // Set theme
  const setTheme = (theme) => {
    document.documentElement.setAttribute(THEME_ATTR, theme);
    localStorage.setItem(STORAGE_KEY, theme);
    updateToggleButton(theme);
  };
  
  // Update toggle button appearance
  const updateToggleButton = (theme) => {
    const toggleBtn = document.querySelector('.theme-toggle');
    if (!toggleBtn) return;
    
    const icon = toggleBtn.querySelector('.icon');
    if (theme === 'dark') {
      toggleBtn.setAttribute('aria-label', 'Switch to light mode');
      if (icon) {
        icon.innerHTML = '☀️';
      }
    } else {
      toggleBtn.setAttribute('aria-label', 'Switch to dark mode');
      if (icon) {
        icon.innerHTML = '🌙';
      }
    }
  };
  
  // Toggle theme
  const toggleTheme = () => {
    const currentTheme = document.documentElement.getAttribute(THEME_ATTR);
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
    
    // Add animation class
    document.body.classList.add('theme-transitioning');
    setTimeout(() => {
      document.body.classList.remove('theme-transitioning');
    }, 300);
  };
  
  // Initialize theme on page load
  const initTheme = () => {
    const savedTheme = getSavedTheme();
    setTheme(savedTheme);
  };
  
  // Create toggle button if it doesn't exist
  const createToggleButton = () => {
    const header = document.querySelector('.site-header__container');
    if (!header || document.querySelector('.theme-toggle')) return;
    
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'theme-toggle';
    toggleBtn.setAttribute('aria-label', 'Toggle theme');
    toggleBtn.innerHTML = '<span class="icon">🌙</span>';
    
    toggleBtn.addEventListener('click', toggleTheme);
    header.appendChild(toggleBtn);
  };
  
  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      initTheme();
      createToggleButton();
    });
  } else {
    initTheme();
    createToggleButton();
  }
  
  // Listen for system theme changes
  if (window.matchMedia) {
    const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
    darkModeQuery.addEventListener('change', (e) => {
      if (!localStorage.getItem(STORAGE_KEY)) {
        setTheme(e.matches ? 'dark' : 'light');
      }
    });
  }
  
})();
