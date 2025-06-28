/**
 * Responsive breakpoints configuration
 * 4 modes: mobile (0-719px), tablet (720-959px), desktop (960-1279px), wide (1280px+)
 */

// Get breakpoints from CSS variables
const getBreakpoint = (name) => {
	const rootStyles = getComputedStyle(document.documentElement);
	const value = rootStyles.getPropertyValue(`--js-breakpoint-${name}`).trim();
	return parseInt(value.replace('px', ''));
};

export const BREAKPOINTS = {
	get tablet() {
		return getBreakpoint('sm');
	},
	get desktop() {
		return getBreakpoint('md');
	},
	get wide() {
		return getBreakpoint('lg');
	}
};

export const isMobile = () => window.innerWidth < BREAKPOINTS.tablet;
export const isTablet = () => window.innerWidth >= BREAKPOINTS.tablet && window.innerWidth < BREAKPOINTS.desktop;
export const isDesktop = () => window.innerWidth >= BREAKPOINTS.desktop && window.innerWidth < BREAKPOINTS.wide;
export const isWide = () => window.innerWidth >= BREAKPOINTS.wide;

export const getCurrentBreakpoint = () => {
	if (isMobile()) return 'mobile';
	if (isTablet()) return 'tablet';
	if (isDesktop()) return 'desktop';
	return 'wide';
};

export const matchesBreakpoint = (breakpoint) => {
	switch (breakpoint) {
		case 'mobile': return isMobile();
		case 'tablet': return isTablet();
		case 'desktop': return isDesktop();
		case 'wide': return isWide();
		default: return false;
	}
};

export const onBreakpointChange = (callback) => {
	let currentBreakpoint = getCurrentBreakpoint();
	
	const checkBreakpoint = () => {
		const newBreakpoint = getCurrentBreakpoint();
		if (currentBreakpoint !== newBreakpoint) {
			currentBreakpoint = newBreakpoint;
			callback(currentBreakpoint);
		}
	};
	
	window.addEventListener('resize', checkBreakpoint);
	
	// Return cleanup function
	return () => window.removeEventListener('resize', checkBreakpoint);
};