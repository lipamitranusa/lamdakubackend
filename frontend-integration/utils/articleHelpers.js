/**
 * Utility functions for article handling and formatting
 */

/**
 * Format date to Indonesian locale
 */
export const formatDate = (dateString, options = {}) => {
  const defaultOptions = {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    ...options
  };

  return new Date(dateString).toLocaleDateString('id-ID', defaultOptions);
};

/**
 * Format date with time
 */
export const formatDateTime = (dateString) => {
  return formatDate(dateString, {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

/**
 * Get relative time (e.g., "2 hours ago")
 */
export const getRelativeTime = (dateString) => {
  const now = new Date();
  const date = new Date(dateString);
  const diffInSeconds = Math.floor((now - date) / 1000);

  const intervals = {
    year: 31536000,
    month: 2592000,
    week: 604800,
    day: 86400,
    hour: 3600,
    minute: 60
  };

  for (const [unit, seconds] of Object.entries(intervals)) {
    const interval = Math.floor(diffInSeconds / seconds);
    if (interval >= 1) {
      return `${interval} ${unit}${interval > 1 ? 's' : ''} ago`;
    }
  }

  return 'Just now';
};

/**
 * Generate proper image URL from backend
 */
export const getImageUrl = (imagePath, baseUrl = 'http://localhost:8000') => {
  if (!imagePath) {
    return '/images/placeholder-article.jpg';
  }
  
  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath;
  }
  
  return `${baseUrl}/storage/${imagePath}`;
};

/**
 * Generate article URL
 */
export const getArticleUrl = (slug, baseUrl = '') => {
  return `${baseUrl}/articles/${slug}`;
};

/**
 * Extract plain text from HTML content
 */
export const stripHtml = (html) => {
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || '';
};

/**
 * Truncate text to specified length
 */
export const truncateText = (text, maxLength = 150, suffix = '...') => {
  if (text.length <= maxLength) return text;
  return text.substring(0, maxLength).trim() + suffix;
};

/**
 * Generate excerpt from content
 */
export const generateExcerpt = (content, maxLength = 150) => {
  const plainText = stripHtml(content);
  return truncateText(plainText, maxLength);
};

/**
 * Calculate reading time estimate
 */
export const calculateReadingTime = (content, wordsPerMinute = 200) => {
  const plainText = stripHtml(content);
  const wordCount = plainText.split(/\s+/).length;
  const readingTime = Math.ceil(wordCount / wordsPerMinute);
  return readingTime;
};

/**
 * Generate SEO-friendly slug
 */
export const generateSlug = (title) => {
  return title
    .toLowerCase()
    .trim()
    .replace(/[^\w\s-]/g, '') // Remove special characters
    .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
    .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
};

/**
 * Validate article data
 */
export const validateArticle = (article) => {
  const errors = [];

  if (!article.title || article.title.trim().length === 0) {
    errors.push('Title is required');
  }

  if (!article.content || article.content.trim().length === 0) {
    errors.push('Content is required');
  }

  if (!article.slug || article.slug.trim().length === 0) {
    errors.push('Slug is required');
  }

  if (article.meta_title && article.meta_title.length > 60) {
    errors.push('Meta title should be 60 characters or less');
  }

  if (article.meta_description && article.meta_description.length > 160) {
    errors.push('Meta description should be 160 characters or less');
  }

  return {
    isValid: errors.length === 0,
    errors
  };
};

/**
 * Format article data for display
 */
export const formatArticleForDisplay = (article) => {
  return {
    ...article,
    formattedDate: formatDate(article.published_at || article.created_at),
    relativeTime: getRelativeTime(article.published_at || article.created_at),
    imageUrl: getImageUrl(article.featured_image),
    url: getArticleUrl(article.slug),
    excerpt: article.excerpt || generateExcerpt(article.content),
    readingTime: article.reading_time || calculateReadingTime(article.content),
    tags: Array.isArray(article.tags) ? article.tags : [],
    gallery: Array.isArray(article.gallery) ? article.gallery : []
  };
};

/**
 * Format multiple articles for display
 */
export const formatArticlesForDisplay = (articles) => {
  return articles.map(formatArticleForDisplay);
};

/**
 * Group articles by category
 */
export const groupArticlesByCategory = (articles) => {
  return articles.reduce((groups, article) => {
    const category = article.category || 'Uncategorized';
    if (!groups[category]) {
      groups[category] = [];
    }
    groups[category].push(article);
    return groups;
  }, {});
};

/**
 * Group articles by date (year/month)
 */
export const groupArticlesByDate = (articles, groupBy = 'month') => {
  return articles.reduce((groups, article) => {
    const date = new Date(article.published_at || article.created_at);
    let key;

    switch (groupBy) {
      case 'year':
        key = date.getFullYear().toString();
        break;
      case 'month':
        key = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
        break;
      case 'day':
        key = date.toISOString().split('T')[0];
        break;
      default:
        key = date.toISOString().split('T')[0];
    }

    if (!groups[key]) {
      groups[key] = [];
    }
    groups[key].push(article);
    return groups;
  }, {});
};

/**
 * Filter articles by search query
 */
export const filterArticlesBySearch = (articles, query) => {
  if (!query.trim()) return articles;

  const searchTerms = query.toLowerCase().split(' ');
  
  return articles.filter(article => {
    const searchableText = [
      article.title,
      article.excerpt,
      article.content,
      article.category,
      ...(article.tags || []),
      article.author?.name
    ].join(' ').toLowerCase();

    return searchTerms.every(term => searchableText.includes(term));
  });
};

/**
 * Sort articles by different criteria
 */
export const sortArticles = (articles, sortBy = 'date', order = 'desc') => {
  const sorted = [...articles].sort((a, b) => {
    let aValue, bValue;

    switch (sortBy) {
      case 'date':
        aValue = new Date(a.published_at || a.created_at);
        bValue = new Date(b.published_at || b.created_at);
        break;
      case 'title':
        aValue = a.title.toLowerCase();
        bValue = b.title.toLowerCase();
        break;
      case 'views':
        aValue = a.view_count || 0;
        bValue = b.view_count || 0;
        break;
      case 'author':
        aValue = (a.author?.name || '').toLowerCase();
        bValue = (b.author?.name || '').toLowerCase();
        break;
      default:
        return 0;
    }

    if (aValue < bValue) return order === 'asc' ? -1 : 1;
    if (aValue > bValue) return order === 'asc' ? 1 : -1;
    return 0;
  });

  return sorted;
};

/**
 * Get unique values from articles (for filters)
 */
export const getUniqueValues = (articles, field) => {
  const values = new Set();
  
  articles.forEach(article => {
    if (field === 'tags' && Array.isArray(article.tags)) {
      article.tags.forEach(tag => values.add(tag));
    } else if (field === 'author') {
      if (article.author?.name) values.add(article.author.name);
    } else if (article[field]) {
      values.add(article[field]);
    }
  });

  return Array.from(values).sort();
};

/**
 * Generate breadcrumb navigation
 */
export const generateBreadcrumbs = (article, basePath = '/articles') => {
  const breadcrumbs = [
    { label: 'Home', url: '/' },
    { label: 'Articles', url: basePath }
  ];

  if (article.category) {
    breadcrumbs.push({
      label: article.category,
      url: `${basePath}?category=${encodeURIComponent(article.category)}`
    });
  }

  breadcrumbs.push({
    label: article.title,
    url: `${basePath}/${article.slug}`,
    current: true
  });

  return breadcrumbs;
};

/**
 * Generate structured data for SEO
 */
export const generateStructuredData = (article, baseUrl = 'http://localhost:3000') => {
  return {
    '@context': 'https://schema.org',
    '@type': 'Article',
    headline: article.title,
    description: article.excerpt || generateExcerpt(article.content),
    image: article.featured_image ? getImageUrl(article.featured_image) : undefined,
    author: {
      '@type': 'Person',
      name: article.author?.name || 'Unknown Author'
    },
    publisher: {
      '@type': 'Organization',
      name: 'Your Organization Name',
      logo: {
        '@type': 'ImageObject',
        url: `${baseUrl}/logo.png`
      }
    },
    datePublished: article.published_at || article.created_at,
    dateModified: article.updated_at,
    mainEntityOfPage: {
      '@type': 'WebPage',
      '@id': `${baseUrl}/articles/${article.slug}`
    }
  };
};

/**
 * Local storage helpers for article preferences
 */
export const articleStorage = {
  // Save reading preferences
  saveReadingPrefs: (prefs) => {
    localStorage.setItem('articleReadingPrefs', JSON.stringify(prefs));
  },

  // Get reading preferences
  getReadingPrefs: () => {
    const saved = localStorage.getItem('articleReadingPrefs');
    return saved ? JSON.parse(saved) : {
      fontSize: 'medium',
      theme: 'light',
      showImages: true
    };
  },

  // Save recently viewed articles
  addToRecentlyViewed: (articleId) => {
    const recent = articleStorage.getRecentlyViewed();
    const updated = [articleId, ...recent.filter(id => id !== articleId)].slice(0, 10);
    localStorage.setItem('recentlyViewedArticles', JSON.stringify(updated));
  },

  // Get recently viewed articles
  getRecentlyViewed: () => {
    const saved = localStorage.getItem('recentlyViewedArticles');
    return saved ? JSON.parse(saved) : [];
  },

  // Clear all article data
  clearAll: () => {
    localStorage.removeItem('articleReadingPrefs');
    localStorage.removeItem('recentlyViewedArticles');
    localStorage.removeItem('articleFavorites');
  }
};

/**
 * URL parameter helpers
 */
export const urlHelpers = {
  // Get URL parameters for article filtering
  getFiltersFromURL: (searchParams) => {
    return {
      category: searchParams.get('category') || '',
      tag: searchParams.get('tag') || '',
      author: searchParams.get('author') || '',
      search: searchParams.get('search') || '',
      page: parseInt(searchParams.get('page')) || 1,
      sort: searchParams.get('sort') || 'date',
      order: searchParams.get('order') || 'desc'
    };
  },

  // Update URL with new filters
  updateURL: (filters, navigate) => {
    const params = new URLSearchParams();
    
    Object.entries(filters).forEach(([key, value]) => {
      if (value && value !== '' && value !== 1) {
        params.set(key, value);
      }
    });

    const queryString = params.toString();
    navigate(`?${queryString}`, { replace: true });
  }
};
