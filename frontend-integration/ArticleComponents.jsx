import React, { useState, useEffect } from 'react';
import ArticleService from '../article-api-service';

/**
 * ArticleCard Component
 * Displays a single article card with image, title, excerpt, and metadata
 */
const ArticleCard = ({ article, variant = 'default' }) => {
    const formatDate = (dateString) => {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const getImageUrl = (image) => {
        if (!image) return '/api/placeholder/400/250';
        return image.startsWith('http') ? image : `http://localhost:8000/storage/${image}`;
    };

    if (variant === 'featured') {
        return (
            <div className="relative overflow-hidden rounded-lg shadow-lg group cursor-pointer">
                <div className="aspect-w-16 aspect-h-9">
                    <img
                        src={getImageUrl(article.featured_image)}
                        alt={article.title}
                        className="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                    />
                </div>
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent" />
                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                    {article.is_featured && (
                        <span className="inline-block px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full mb-2">
                            Featured
                        </span>
                    )}
                    <h3 className="text-xl font-bold mb-2 line-clamp-2">{article.title}</h3>
                    <p className="text-gray-200 text-sm mb-3 line-clamp-2">{article.excerpt}</p>
                    <div className="flex items-center justify-between text-xs text-gray-300">
                        <span>By {article.author?.name}</span>
                        <span>{formatDate(article.published_at)}</span>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <div className="aspect-w-16 aspect-h-9">
                <img
                    src={getImageUrl(article.featured_image)}
                    alt={article.title}
                    className="w-full h-48 object-cover"
                />
            </div>
            <div className="p-6">
                <div className="flex items-center justify-between mb-2">
                    {article.is_featured && (
                        <span className="inline-block px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded-full">
                            Featured
                        </span>
                    )}
                    <span className="text-xs text-gray-500">
                        {formatDate(article.published_at)}
                    </span>
                </div>
                
                <h3 className="text-xl font-semibold mb-3 line-clamp-2 hover:text-blue-600 transition-colors">
                    {article.title}
                </h3>
                
                <p className="text-gray-600 text-sm mb-4 line-clamp-3">
                    {article.excerpt}
                </p>
                
                <div className="flex items-center justify-between">
                    <div className="flex items-center space-x-2">
                        <span className="text-xs text-gray-500">
                            By {article.author?.name}
                        </span>
                        {article.reading_time && (
                            <span className="text-xs text-gray-400">
                                • {article.reading_time} min read
                            </span>
                        )}
                    </div>
                    
                    {article.view_count && (
                        <span className="text-xs text-gray-400">
                            {article.view_count} views
                        </span>
                    )}
                </div>
                
                {article.categories && article.categories.length > 0 && (
                    <div className="mt-4 flex flex-wrap gap-2">
                        {article.categories.map((category, index) => (
                            <span
                                key={index}
                                className="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded"
                            >
                                {category}
                            </span>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
};

/**
 * ArticleList Component
 * Displays a list of articles with pagination and filtering
 */
const ArticleList = ({ 
    apiEndpoint = 'articles',
    showFilters = true,
    showPagination = true,
    pageSize = 10,
    className = ''
}) => {
    const [articles, setArticles] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [pagination, setPagination] = useState({});
    const [filters, setFilters] = useState({
        page: 1,
        per_page: pageSize,
        status: 'published',
        search: '',
        category: '',
        sort_by: 'published_at',
        sort_order: 'desc'
    });
    
    const [categories, setCategories] = useState([]);
    const articleService = new ArticleService();

    useEffect(() => {
        loadCategories();
    }, []);

    useEffect(() => {
        loadArticles();
    }, [filters]);

    const loadCategories = async () => {
        try {
            const categoriesData = await articleService.getCategories();
            setCategories(categoriesData.data || []);
        } catch (err) {
            console.error('Failed to load categories:', err);
        }
    };

    const loadArticles = async () => {
        setLoading(true);
        setError(null);
        
        try {
            let data;
            
            switch (apiEndpoint) {
                case 'featured':
                    data = await articleService.getFeaturedArticles(filters.per_page);
                    break;
                case 'latest':
                    data = await articleService.getLatestArticles(filters.per_page);
                    break;
                case 'popular':
                    data = await articleService.getPopularArticles(filters.per_page);
                    break;
                default:
                    data = await articleService.getArticles(filters);
            }
            
            setArticles(data.data || []);
            setPagination(data.meta || {});
        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const handleFilterChange = (key, value) => {
        setFilters(prev => ({
            ...prev,
            [key]: value,
            ...(key !== 'page' && { page: 1 }) // Reset page when other filters change
        }));
    };

    const handleSearch = (e) => {
        e.preventDefault();
        loadArticles();
    };

    if (loading) {
        return (
            <div className={`${className}`}>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {[...Array(6)].map((_, index) => (
                        <div key={index} className="animate-pulse">
                            <div className="bg-gray-300 h-48 rounded-lg mb-4"></div>
                            <div className="h-4 bg-gray-300 rounded mb-2"></div>
                            <div className="h-4 bg-gray-300 rounded mb-2 w-3/4"></div>
                            <div className="h-3 bg-gray-300 rounded w-1/2"></div>
                        </div>
                    ))}
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className={`${className}`}>
                <div className="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 className="text-red-800 font-semibold mb-2">Error Loading Articles</h3>
                    <p className="text-red-600">{error}</p>
                    <button
                        onClick={loadArticles}
                        className="mt-3 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                    >
                        Try Again
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className={`${className}`}>
            {/* Filters */}
            {showFilters && (
                <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div className="flex flex-wrap gap-4 items-center">
                        {/* Search */}
                        <form onSubmit={handleSearch} className="flex-1 min-w-64">
                            <div className="relative">
                                <input
                                    type="text"
                                    placeholder="Search articles..."
                                    value={filters.search}
                                    onChange={(e) => handleFilterChange('search', e.target.value)}
                                    className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </form>

                        {/* Category Filter */}
                        <select
                            value={filters.category}
                            onChange={(e) => handleFilterChange('category', e.target.value)}
                            className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">All Categories</option>
                            {categories.map((category) => (
                                <option key={category} value={category}>
                                    {category}
                                </option>
                            ))}
                        </select>

                        {/* Sort */}
                        <select
                            value={`${filters.sort_by}-${filters.sort_order}`}
                            onChange={(e) => {
                                const [sort_by, sort_order] = e.target.value.split('-');
                                handleFilterChange('sort_by', sort_by);
                                handleFilterChange('sort_order', sort_order);
                            }}
                            className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="published_at-desc">Newest First</option>
                            <option value="published_at-asc">Oldest First</option>
                            <option value="title-asc">Title A-Z</option>
                            <option value="title-desc">Title Z-A</option>
                            <option value="view_count-desc">Most Popular</option>
                        </select>
                    </div>
                </div>
            )}

            {/* Articles Grid */}
            {articles.length === 0 ? (
                <div className="text-center py-12">
                    <svg className="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 className="mt-2 text-sm font-medium text-gray-900">No articles found</h3>
                    <p className="mt-1 text-sm text-gray-500">
                        Try adjusting your search criteria or filters.
                    </p>
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {articles.map((article) => (
                        <ArticleCard
                            key={article.id}
                            article={article}
                            variant={apiEndpoint === 'featured' ? 'featured' : 'default'}
                        />
                    ))}
                </div>
            )}

            {/* Pagination */}
            {showPagination && pagination.last_page > 1 && (
                <div className="flex items-center justify-between mt-8">
                    <div className="text-sm text-gray-700">
                        Showing {pagination.from} to {pagination.to} of {pagination.total} results
                    </div>
                    
                    <div className="flex space-x-2">
                        <button
                            onClick={() => handleFilterChange('page', filters.page - 1)}
                            disabled={filters.page <= 1}
                            className="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                        >
                            Previous
                        </button>
                        
                        {[...Array(Math.min(5, pagination.last_page))].map((_, index) => {
                            const page = index + 1;
                            return (
                                <button
                                    key={page}
                                    onClick={() => handleFilterChange('page', page)}
                                    className={`px-3 py-1 border rounded ${
                                        filters.page === page
                                            ? 'bg-blue-600 text-white border-blue-600'
                                            : 'border-gray-300 hover:bg-gray-50'
                                    }`}
                                >
                                    {page}
                                </button>
                            );
                        })}
                        
                        <button
                            onClick={() => handleFilterChange('page', filters.page + 1)}
                            disabled={filters.page >= pagination.last_page}
                            className="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
                        >
                            Next
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
};

export { ArticleCard, ArticleList };
