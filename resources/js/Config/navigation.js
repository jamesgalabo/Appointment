/**
 * Single Source of Truth for Sidebar Navigation (SOLID Principle: SRP)
 * No emojis - uses icon keys mapped to SVG paths in the AppLayout NavIcon renderer.
 */

export const ADMIN_NAVIGATION = [
  {
    label: 'Analytics & Overview',
    items: [
      { label: 'Dashboard', href: '/admin/dashboard', icon: 'grid' },
      { label: 'Analytics & Reports', href: '/admin/reports', icon: 'bar-chart' },
    ],
  },
  {
    label: 'Management',
    items: [
      { label: 'Boarding Houses', href: '/admin/houses', icon: 'building' },
      { label: 'Appointments', href: '/admin/appointments', icon: 'calendar' },
      { label: 'Reservations', href: '/admin/reservations', icon: 'clipboard' },
      { label: 'Platform Users', href: '/admin/users', icon: 'users' },
    ],
  },
];

export const OWNER_NAVIGATION = [
  {
    label: 'Overview',
    items: [
      { label: 'Dashboard', href: '/owner/dashboard', icon: 'grid' },
      { label: 'Live Messages', href: '/messages', icon: 'chat' },
      { label: 'Rooms & Units', href: '/owner/rooms', icon: 'layers' },
    ],
  },
  {
    label: 'Bookings & Approvals',
    items: [
      { label: 'Reservations', href: '/owner/reservations', icon: 'clipboard' },
      { label: 'Appointments', href: '/owner/appointments', icon: 'calendar' },
      { label: 'Current Tenants', href: '/owner/tenants', icon: 'users' },
    ],
  },
  {
    label: 'Settings',
    items: [
      { label: 'House Profile & Map', href: '/owner/profile', icon: 'settings' },
    ],
  },
];

export const STUDENT_NAVIGATION = [
  {
    label: 'Explore',
    items: [
      { label: 'Find Boarding Houses', href: '/student/explore', icon: 'search' },
      { label: 'Live Messages', href: '/messages', icon: 'chat' },
      { label: 'Dashboard', href: '/student/dashboard', icon: 'grid' },
    ],
  },
  {
    label: 'My Bookings',
    items: [
      { label: 'My Appointments', href: '/student/appointments', icon: 'calendar' },
      { label: 'My Reservations', href: '/student/reservations', icon: 'clipboard' },
    ],
  },
  {
    label: 'Account',
    items: [
      { label: 'My Profile', href: '/student/profile', icon: 'user' },
    ],
  },
];

/**
 * Get the standardized navigation tree based on user role.
 * @param {string} role - 'admin' | 'owner' | 'student'
 */
export function getNavigationForRole(role) {
  switch (role) {
    case 'admin':
      return ADMIN_NAVIGATION;
    case 'owner':
      return OWNER_NAVIGATION;
    case 'student':
    default:
      return STUDENT_NAVIGATION;
  }
}

/**
 * SVG path data for each icon key (24x24, stroke-based, fill=none).
 */
export const NAV_ICONS = {
  chat:        'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
  grid:        'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
  'bar-chart': 'M18 20V10M12 20V4M6 20v-6',
  building:    'M3 21h18M9 8h1m-1 4h1m-1 4h1M13 8h1m-1 4h1m-1 4h1M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16',
  calendar:    'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
  clipboard:   'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
  users:       'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m8-10a4 4 0 100-8 4 4 0 000 8zm9 3a3 3 0 11-6 0 3 3 0 016 0zm-1 7v-2a3 3 0 00-3-3h-1',
  layers:      'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5',
  settings:    'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
  search:      'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
  user:        'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
  logout:      'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
  pin:         'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
  search2:     'M11 4a7 7 0 100 14A7 7 0 0011 4zM21 21l-4.35-4.35',
};
