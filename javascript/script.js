/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

import { isDesktop, isWide, onBreakpointChange } from './breakpoints.js';

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
  const mobileToggle = document.querySelector('.estjo-header__mobile-toggle');
  const mobileMenu = document.querySelector('.estjo-header__mobile-menu');
  
  if (mobileToggle && mobileMenu) {
    // Toggle menu function
    const toggleMenu = (open) => {
      if (open) {
        mobileMenu.classList.add('is-open');
        mobileToggle.classList.add('is-open');
        mobileToggle.setAttribute('aria-expanded', 'true');
      } else {
        mobileMenu.classList.remove('is-open');
        mobileToggle.classList.remove('is-open');
        mobileToggle.setAttribute('aria-expanded', 'false');
      }
    };
    
    // Toggle on click
    mobileToggle.addEventListener('click', function() {
      const isOpen = mobileMenu.classList.contains('is-open');
      toggleMenu(!isOpen);
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
      if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
        toggleMenu(false);
      }
    });
    
    // Close menu when switching to desktop or wide (above 960px)
    const checkAndCloseMenu = () => {
      if (isDesktop() || isWide()) {
        toggleMenu(false);
      }
    };
    
    // Check on breakpoint change
    onBreakpointChange(checkAndCloseMenu);
    
    // Also check on every resize
    window.addEventListener('resize', checkAndCloseMenu);
  }
});
