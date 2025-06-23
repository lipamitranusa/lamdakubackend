import { useState, useEffect, useCallback } from 'react';
import ArticleService from '../services/article-api-service';

// Initialize service
const articleService = new ArticleService();

/**
 * Custom hook for fetching articles with various filters and pagination
 */
export const useArticles = (initialParams = {}) => {
  const [articles, setArticles] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [pagination, setPagination] = useState(null);
  const [params, setParams] = useState({
    page: 1,
    per_page: 10,
    status: 'published',
    ...initialParams
  });

  const fetchArticles = useCallback(async (newParams = {}) => {
    try {
      setLoading(true);
      setError(null);
      
      const mergedParams = { ...params, ...newParams };
      const response = await articleService.getArticles(mergedParams);
      
      setArticles(response.data || []);
      setPagination(response.meta || null);
      setParams(mergedParams);
    } catch (err) {
      setError(err.message);
      console.error('Error fetching articles:', err);
    } finally {
      setLoading(false);
    }
  }, [params]);

  useEffect(() => {
    fetchArticles();
  }, []);

  const refresh = useCallback(() => {
    fetchArticles(params);
  }, [fetchArticles, params]);

  const loadMore = useCallback(() => {
    if (pagination && pagination.current_page < pagination.last_page) {
      fetchArticles({ ...params, page: pagination.current_page + 1 });
    }
  }, [fetchArticles, pagination, params]);

  const updateFilters = useCallback((newFilters) => {
    fetchArticles({ ...params, ...newFilters, page: 1 });
  }, [fetchArticles, params]);

  const goToPage = useCallback((page) => {
    fetchArticles({ ...params, page });
  }, [fetchArticles, params]);

  return {
    articles,
    loading,
    error,
    pagination,
    params,
    refresh,
    loadMore,
    updateFilters,
    goToPage,
    hasMore: pagination ? pagination.current_page < pagination.last_page : false
  };
};

/**
 * Custom hook for fetching a single article by slug
 */
export const useArticle = (slug) => {
  const [article, setArticle] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [relatedArticles, setRelatedArticles] = useState([]);

  const fetchArticle = useCallback(async () => {
    if (!slug) return;

    try {
      setLoading(true);
      setError(null);
      
      const response = await articleService.getArticle(slug);
      setArticle(response.data);
      
      // Set related articles if available
      if (response.data?.related_articles) {
        setRelatedArticles(response.data.related_articles);
      }
    } catch (err) {
      setError(err.message);
      console.error('Error fetching article:', err);
    } finally {
      setLoading(false);
    }
  }, [slug]);

  useEffect(() => {
    fetchArticle();
  }, [fetchArticle]);

  const refresh = useCallback(() => {
    fetchArticle();
  }, [fetchArticle]);

  return {
    article,
    loading,
    error,
    relatedArticles,
    refresh
  };
};

/**
 * Custom hook for fetching featured articles
 */
export const useFeaturedArticles = (limit = 5) => {
  const [articles, setArticles] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchFeaturedArticles = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await articleService.getFeaturedArticles(limit);
      setArticles(response.data || []);
    } catch (err) {
      setError(err.message);
      console.error('Error fetching featured articles:', err);
    } finally {
      setLoading(false);
    }
  }, [limit]);

  useEffect(() => {
    fetchFeaturedArticles();
  }, [fetchFeaturedArticles]);

  const refresh = useCallback(() => {
    fetchFeaturedArticles();
  }, [fetchFeaturedArticles]);

  return {
    articles,
    loading,
    error,
    refresh
  };
};

/**
 * Custom hook for fetching latest articles
 */
export const useLatestArticles = (limit = 10, excludeIds = []) => {
  const [articles, setArticles] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchLatestArticles = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      
      const response = await articleService.getLatestArticles(limit);
      let filteredArticles = response.data || [];
      
      // Filter out excluded IDs
      if (excludeIds.length > 0) {
        filteredArticles = filteredArticles.filter(
          article => !excludeIds.includes(article.id)
        );
      }
      
      setArticles(filteredArticles);
    } catch (err) {
      setError(err.message);
      console.error('Error fetching latest articles:', err);
    } finally {
      setLoading(false);
    }
  }, [limit, excludeIds]);

  useEffect(() => {
    fetchLatestArticles();
  }, [fetchLatestArticles]);

  const refresh = useCallback(() => {
    fetchLatestArticles();
  }, [fetchLatestArticles]);

  return {
    articles,
    loading,
    error,
    refresh
  };
};

/**
 * Custom hook for article search with debounced queries
 */
export const useArticleSearch = (initialQuery = '', debounceMs = 300) => {
  const [query, setQuery] = useState(initialQuery);
  const [debouncedQuery, setDebouncedQuery] = useState(initialQuery);
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [suggestions, setSuggestions] = useState([]);

  // Debounce the search query
  useEffect(() => {
    const timer = setTimeout(() => {
      setDebouncedQuery(query);
    }, debounceMs);

    return () => clearTimeout(timer);
  }, [query, debounceMs]);

  // Perform search when debounced query changes
  useEffect(() => {
    const performSearch = async () => {
      if (!debouncedQuery.trim()) {
        setResults([]);
        setSuggestions([]);
        return;
      }

      try {
        setLoading(true);
        setError(null);
        
        const response = await articleService.searchArticles(debouncedQuery);
        setResults(response.data || []);
        
        // Get limited suggestions for autocomplete
        if (response.data) {
          setSuggestions(response.data.slice(0, 5));
        }
      } catch (err) {
        setError(err.message);
        console.error('Error searching articles:', err);
      } finally {
        setLoading(false);
      }
    };

    performSearch();
  }, [debouncedQuery]);

  const clearSearch = useCallback(() => {
    setQuery('');
    setResults([]);
    setSuggestions([]);
    setError(null);
  }, []);

  const searchWithFilters = useCallback(async (filters = {}) => {
    if (!query.trim()) return;

    try {
      setLoading(true);
      setError(null);
      
      const response = await articleService.searchArticles(query, filters);
      setResults(response.data || []);
    } catch (err) {
      setError(err.message);
      console.error('Error searching with filters:', err);
    } finally {
      setLoading(false);
    }
  }, [query]);

  return {
    query,
    setQuery,
    results,
    suggestions,
    loading,
    error,
    clearSearch,
    searchWithFilters
  };
};

/**
 * Custom hook for fetching article categories and tags
 */
export const useArticleFilters = () => {
  const [categories, setCategories] = useState([]);
  const [tags, setTags] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchFilters = useCallback(async () => {
    try {
      setLoading(true);
      setError(null);
      
      const [categoriesResponse, tagsResponse] = await Promise.all([
        articleService.getCategories(),
        articleService.getTags()
      ]);
      
      setCategories(categoriesResponse || []);
      setTags(tagsResponse || []);
    } catch (err) {
      setError(err.message);
      console.error('Error fetching filters:', err);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    fetchFilters();
  }, [fetchFilters]);

  const refresh = useCallback(() => {
    fetchFilters();
  }, [fetchFilters]);

  return {
    categories,
    tags,
    loading,
    error,
    refresh
  };
};

/**
 * Custom hook for managing article favorites (client-side only)
 */
export const useArticleFavorites = () => {
  const [favorites, setFavorites] = useState(() => {
    const saved = localStorage.getItem('articleFavorites');
    return saved ? JSON.parse(saved) : [];
  });

  const addToFavorites = useCallback((articleId) => {
    setFavorites(prev => {
      const updated = [...prev, articleId];
      localStorage.setItem('articleFavorites', JSON.stringify(updated));
      return updated;
    });
  }, []);

  const removeFromFavorites = useCallback((articleId) => {
    setFavorites(prev => {
      const updated = prev.filter(id => id !== articleId);
      localStorage.setItem('articleFavorites', JSON.stringify(updated));
      return updated;
    });
  }, []);

  const toggleFavorite = useCallback((articleId) => {
    if (favorites.includes(articleId)) {
      removeFromFavorites(articleId);
    } else {
      addToFavorites(articleId);
    }
  }, [favorites, addToFavorites, removeFromFavorites]);

  const isFavorite = useCallback((articleId) => {
    return favorites.includes(articleId);
  }, [favorites]);

  const clearFavorites = useCallback(() => {
    setFavorites([]);
    localStorage.removeItem('articleFavorites');
  }, []);

  return {
    favorites,
    addToFavorites,
    removeFromFavorites,
    toggleFavorite,
    isFavorite,
    clearFavorites
  };
};

/**
 * Custom hook for tracking article reading progress
 */
export const useReadingProgress = () => {
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    const updateProgress = () => {
      const scrollTop = window.pageYOffset;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const scrollPercent = (scrollTop / docHeight) * 100;
      setProgress(Math.min(Math.max(scrollPercent, 0), 100));
    };

    window.addEventListener('scroll', updateProgress);
    return () => window.removeEventListener('scroll', updateProgress);
  }, []);

  return progress;
};
