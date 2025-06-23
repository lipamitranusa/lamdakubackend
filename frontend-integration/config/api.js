/**
 * API Configuration for Article Management System
 */

// Environment-based configuration
export const API_CONFIG = {
  // Base URLs
  BASE_URL: process.env.REACT_APP_API_BASE_URL || 'http://localhost:8000/api/v1',
  BACKEND_URL: process.env.REACT_APP_BACKEND_URL || 'http://localhost:8000',
  
  // Endpoints
  ENDPOINTS: {
    ARTICLES: '/articles',
    FEATURED: '/articles/featured',
    LATEST: '/articles/latest',
    POPULAR: '/articles/popular',
    SEARCH: '/articles/search',
    CATEGORIES: '/articles/categories',
    TAGS: '/articles/tags'
  },

  // Default request settings
  DEFAULTS: {
    TIMEOUT: 10000, // 10 seconds
    RETRY_ATTEMPTS: 3,
    RETRY_DELAY: 1000, // 1 second
  },

  // Pagination defaults
  PAGINATION: {
    DEFAULT_PAGE_SIZE: 10,
    MAX_PAGE_SIZE: 50
  },

  // Image settings
  IMAGES: {
    PLACEHOLDER: '/images/placeholder-article.jpg',
    DEFAULT_QUALITY: 80,
    LAZY_LOADING: true
  },

  // Cache settings
  CACHE: {
    ENABLE: true,
    TTL: 300000, // 5 minutes
    MAX_SIZE: 100 // Maximum cached items
  }
};

/**
 * HTTP Status Codes
 */
export const HTTP_STATUS = {
  OK: 200,
  CREATED: 201,
  NO_CONTENT: 204,
  BAD_REQUEST: 400,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  UNPROCESSABLE_ENTITY: 422,
  INTERNAL_SERVER_ERROR: 500,
  SERVICE_UNAVAILABLE: 503
};

/**
 * Article Status Constants
 */
export const ARTICLE_STATUS = {
  DRAFT: 'draft',
  PUBLISHED: 'published',
  ARCHIVED: 'archived'
};

/**
 * Sort Options
 */
export const SORT_OPTIONS = {
  DATE_DESC: { field: 'published_at', order: 'desc', label: 'Newest First' },
  DATE_ASC: { field: 'published_at', order: 'asc', label: 'Oldest First' },
  TITLE_ASC: { field: 'title', order: 'asc', label: 'Title A-Z' },
  TITLE_DESC: { field: 'title', order: 'desc', label: 'Title Z-A' },
  VIEWS_DESC: { field: 'view_count', order: 'desc', label: 'Most Popular' },
  VIEWS_ASC: { field: 'view_count', order: 'asc', label: 'Least Popular' }
};

/**
 * Error Messages
 */
export const ERROR_MESSAGES = {
  NETWORK_ERROR: 'Network error. Please check your connection.',
  SERVER_ERROR: 'Server error. Please try again later.',
  NOT_FOUND: 'The requested article was not found.',
  UNAUTHORIZED: 'You are not authorized to access this resource.',
  FORBIDDEN: 'Access to this resource is forbidden.',
  VALIDATION_ERROR: 'Please check your input and try again.',
  GENERIC_ERROR: 'An unexpected error occurred. Please try again.'
};

/**
 * Loading States
 */
export const LOADING_STATES = {
  IDLE: 'idle',
  LOADING: 'loading',
  SUCCESS: 'success',
  ERROR: 'error'
};

/**
 * Local Storage Keys
 */
export const STORAGE_KEYS = {
  READING_PREFERENCES: 'articleReadingPrefs',
  RECENTLY_VIEWED: 'recentlyViewedArticles',
  FAVORITES: 'articleFavorites',
  SEARCH_HISTORY: 'articleSearchHistory',
  FILTERS: 'articleFilters'
};

/**
 * Theme Configuration
 */
export const THEME_CONFIG = {
  READING_THEMES: {
    LIGHT: 'light',
    DARK: 'dark',
    SEPIA: 'sepia'
  },
  FONT_SIZES: {
    SMALL: 'text-sm',
    MEDIUM: 'text-base',
    LARGE: 'text-lg',
    EXTRA_LARGE: 'text-xl'
  }
};

/**
 * Image Configuration
 */
export const IMAGE_CONFIG = {
  FORMATS: ['jpg', 'jpeg', 'png', 'webp', 'svg'],
  MAX_SIZE: 5 * 1024 * 1024, // 5MB
  COMPRESSION_QUALITY: 0.8,
  THUMBNAIL_SIZES: {
    SMALL: { width: 150, height: 150 },
    MEDIUM: { width: 300, height: 200 },
    LARGE: { width: 600, height: 400 },
    HERO: { width: 1200, height: 600 }
  }
};

/**
 * SEO Configuration
 */
export const SEO_CONFIG = {
  META_TITLE_MAX_LENGTH: 60,
  META_DESCRIPTION_MAX_LENGTH: 160,
  KEYWORDS_MAX_COUNT: 10,
  OG_TITLE_MAX_LENGTH: 95,
  OG_DESCRIPTION_MAX_LENGTH: 200
};

/**
 * Performance Configuration
 */
export const PERFORMANCE_CONFIG = {
  LAZY_LOADING: {
    ENABLED: true,
    ROOT_MARGIN: '50px',
    THRESHOLD: 0.1
  },
  INFINITE_SCROLL: {
    ENABLED: true,
    THRESHOLD: 0.8, // Load more when 80% scrolled
    BUFFER_SIZE: 3 // Load 3 pages ahead
  },
  DEBOUNCE: {
    SEARCH: 300, // ms
    SCROLL: 100, // ms
    RESIZE: 250 // ms
  }
};

/**
 * Analytics Configuration
 */
export const ANALYTICS_CONFIG = {
  TRACK_PAGE_VIEWS: true,
  TRACK_READING_TIME: true,
  TRACK_SCROLL_DEPTH: true,
  READING_TIME_INTERVALS: [25, 50, 75, 100], // Percentages
  SCROLL_DEPTH_INTERVALS: [25, 50, 75, 100] // Percentages
};

/**
 * Feature Flags
 */
export const FEATURE_FLAGS = {
  ENABLE_COMMENTS: process.env.REACT_APP_ENABLE_COMMENTS === 'true',
  ENABLE_SHARING: process.env.REACT_APP_ENABLE_SHARING !== 'false',
  ENABLE_FAVORITES: process.env.REACT_APP_ENABLE_FAVORITES !== 'false',
  ENABLE_READING_TIME: process.env.REACT_APP_ENABLE_READING_TIME !== 'false',
  ENABLE_VIEW_COUNT: process.env.REACT_APP_ENABLE_VIEW_COUNT !== 'false',
  ENABLE_RELATED_ARTICLES: process.env.REACT_APP_ENABLE_RELATED !== 'false',
  ENABLE_SEARCH_SUGGESTIONS: process.env.REACT_APP_ENABLE_SEARCH_SUGGESTIONS !== 'false'
};

/**
 * Validation Rules
 */
export const VALIDATION_RULES = {
  TITLE: {
    MIN_LENGTH: 5,
    MAX_LENGTH: 200
  },
  EXCERPT: {
    MIN_LENGTH: 20,
    MAX_LENGTH: 500
  },
  CONTENT: {
    MIN_LENGTH: 100,
    MAX_LENGTH: 50000
  },
  SLUG: {
    MIN_LENGTH: 3,
    MAX_LENGTH: 100,
    PATTERN: /^[a-z0-9-]+$/
  },
  SEARCH: {
    MIN_LENGTH: 2,
    MAX_LENGTH: 100
  }
};

/**
 * Default Values
 */
export const DEFAULTS = {
  PAGINATION: {
    PAGE: 1,
    PER_PAGE: 10
  },
  SORT: {
    FIELD: 'published_at',
    ORDER: 'desc'
  },
  FILTERS: {
    STATUS: 'published',
    CATEGORY: '',
    TAG: '',
    AUTHOR: '',
    SEARCH: ''
  }
};

/**
 * API Rate Limiting
 */
export const RATE_LIMITING = {
  REQUESTS_PER_MINUTE: 60,
  BURST_SIZE: 10,
  WINDOW_SIZE: 60000 // 1 minute in milliseconds
};

/**
 * Export environment helper
 */
export const getEnvironment = () => {
  return process.env.NODE_ENV || 'development';
};

/**
 * Check if in development mode
 */
export const isDevelopment = () => {
  return getEnvironment() === 'development';
};

/**
 * Check if in production mode
 */
export const isProduction = () => {
  return getEnvironment() === 'production';
};

/**
 * Get full API URL
 */
export const getApiUrl = (endpoint = '') => {
  return `${API_CONFIG.BASE_URL}${endpoint}`;
};

/**
 * Get full backend URL
 */
export const getBackendUrl = (path = '') => {
  return `${API_CONFIG.BACKEND_URL}${path}`;
};

/**
 * Build URL with query parameters
 */
export const buildUrl = (baseUrl, params = {}) => {
  const url = new URL(baseUrl, window.location.origin);
  
  Object.entries(params).forEach(([key, value]) => {
    if (value !== null && value !== undefined && value !== '') {
      url.searchParams.append(key, value);
    }
  });
  
  return url.toString();
};

export default API_CONFIG;
