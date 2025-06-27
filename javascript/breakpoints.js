/**
 * Responsive breakpoints configuration
 * Only 2 modes: mobile (0-1023px) and desktop (1024px+)
 */

// Get breakpoint from CSS variable
const getBreakpoint = () => {
	const rootStyles = getComputedStyle(document.documentElement);
	return parseInt(rootStyles.getPropertyValue('--breakpoint-desktop')) || 1024;
};

export const BREAKPOINTS = {
	get desktop() {
		return getBreakpoint();
	}
};

export const isMobile = () => window.innerWidth < BREAKPOINTS.desktop;
export const isDesktop = () => window.innerWidth >= BREAKPOINTS.desktop;

export const matchesBreakpoint = (breakpoint) => {
	if (breakpoint === 'mobile') {
		return isMobile();
	}
	if (breakpoint === 'desktop') {
		return isDesktop();
	}
	return false;
};

export const onBreakpointChange = (callback) => {
	let currentBreakpoint = isDesktop() ? 'desktop' : 'mobile';
	
	const checkBreakpoint = () => {
		const newBreakpoint = isDesktop() ? 'desktop' : 'mobile';
		if (currentBreakpoint !== newBreakpoint) {
			currentBreakpoint = newBreakpoint;
			callback(currentBreakpoint);
		}
	};
	
	window.addEventListener('resize', checkBreakpoint);
	
	// Return cleanup function
	return () => window.removeEventListener('resize', checkBreakpoint);
};