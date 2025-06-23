import React, { useState, useEffect } from 'react';
import { useArticles, useArticleSearch, useArticleFilters } from '../hooks/useArticles';
import { formatDate, getImageUrl, truncateText } from '../utils/articleHelpers';

/**
 * Complete Articles Page Component with all features
 */
export const ArticlesPage = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilters, setActiveFilters] = useState({
    category: '',
    tag: '',
    author: '',
    sort: 'date',
    order: 'desc'
  });

  const {
    articles,
    loading,
    error,
    pagination,
    updateFilters,
    goToPage
  } = useArticles({
    per_page: 12,
    ...activeFilters,
    search: searchQuery
  });

  const { categories, tags } = useArticleFilters();

  const handleFilterChange = (filterType, value) => {
    const newFilters = { ...activeFilters, [filterType]: value };
    setActiveFilters(newFilters);
    updateFilters(newFilters);
  };

  const handleSearch = (query) => {
    setSearchQuery(query);
    updateFilters({ ...activeFilters, search: query });
  };

  const clearFilters = () => {
    const clearedFilters = {
      category: '',
      tag: '',
      author: '',
      sort: 'date',
      order: 'desc'
    };
    setActiveFilters(clearedFilters);
    setSearchQuery('');
    updateFilters(clearedFilters);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4 py-6">
          <div className="flex justify-between items-center mb-6">
            <div>
              <h1 className="text-3xl font-bold text-gray-900">Articles</h1>
              <p className="text-gray-600 mt-2">
                {pagination ? `${pagination.total} articles found` : 'Discover our latest insights'}
              </p>
            </div>
            
            {/* Search */}
            <div className="w-full max-w-md">
              <div className="relative">
                <input
                  type="text"
                  placeholder="Search articles..."
                  value={searchQuery}
                  onChange={(e) => handleSearch(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg className="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>

          {/* Filters */}
          <div className="flex flex-wrap gap-4 items-center">
            {/* Category Filter */}
            <select
              value={activeFilters.category}
              onChange={(e) => handleFilterChange('category', e.target.value)}
              className="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Categories</option>
              {categories.map((category) => (
                <option key={category} value={category}>
                  {category}
                </option>
              ))}
            </select>

            {/* Tag Filter */}
            <select
              value={activeFilters.tag}
              onChange={(e) => handleFilterChange('tag', e.target.value)}
              className="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="">All Tags</option>
              {tags.slice(0, 20).map((tag) => (
                <option key={tag} value={tag}>
                  #{tag}
                </option>
              ))}
            </select>

            {/* Sort Filter */}
            <select
              value={`${activeFilters.sort}-${activeFilters.order}`}
              onChange={(e) => {
                const [sort, order] = e.target.value.split('-');
                handleFilterChange('sort', sort);
                handleFilterChange('order', order);
              }}
              className="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
              <option value="date-desc">Newest First</option>
              <option value="date-asc">Oldest First</option>
              <option value="title-asc">Title A-Z</option>
              <option value="title-desc">Title Z-A</option>
              <option value="view_count-desc">Most Popular</option>
            </select>

            {/* Clear Filters */}
            {(activeFilters.category || activeFilters.tag || searchQuery) && (
              <button
                onClick={clearFilters}
                className="px-4 py-2 text-gray-600 hover:text-gray-800 underline"
              >
                Clear Filters
              </button>
            )}
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto px-4 py-8">
        {loading ? (
          <ArticleGridSkeleton />
        ) : error ? (
          <ErrorMessage error={error} />
        ) : articles.length === 0 ? (
          <EmptyState searchQuery={searchQuery} filters={activeFilters} />
        ) : (
          <>
            <ArticleGrid articles={articles} />
            {pagination && pagination.last_page > 1 && (
              <Pagination
                pagination={pagination}
                onPageChange={goToPage}
              />
            )}
          </>
        )}
      </div>
    </div>
  );
};

/**
 * Article Grid Component
 */
const ArticleGrid = ({ articles }) => (
  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    {articles.map((article) => (
      <ArticleGridCard key={article.id} article={article} />
    ))}
  </div>
);

/**
 * Article Grid Card Component
 */
const ArticleGridCard = ({ article }) => (
  <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
    {/* Image */}
    <div className="relative h-48 overflow-hidden">
      <img
        src={getImageUrl(article.featured_image)}
        alt={article.title}
        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        loading="lazy"
      />
      {article.is_featured && (
        <div className="absolute top-3 left-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
          Featured
        </div>
      )}
      {article.reading_time && (
        <div className="absolute top-3 right-3 bg-black bg-opacity-70 text-white px-2 py-1 rounded text-xs">
          {article.reading_time} min read
        </div>
      )}
    </div>

    {/* Content */}
    <div className="p-4">
      {/* Meta */}
      <div className="flex items-center justify-between text-xs text-gray-500 mb-2">
        <span>{formatDate(article.published_at || article.created_at)}</span>
        {article.view_count > 0 && (
          <span>{article.view_count} views</span>
        )}
      </div>

      {/* Category */}
      {article.category && (
        <span className="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mb-2">
          {article.category}
        </span>
      )}

      {/* Title */}
      <h3 className="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
        <a href={`/articles/${article.slug}`}>
          {article.title}
        </a>
      </h3>

      {/* Excerpt */}
      {article.excerpt && (
        <p className="text-gray-600 text-sm mb-3 line-clamp-3">
          {article.excerpt}
        </p>
      )}

      {/* Author */}
      {article.author && (
        <div className="flex items-center text-xs text-gray-500">
          <span>By {article.author.name}</span>
        </div>
      )}

      {/* Tags */}
      {article.tags && article.tags.length > 0 && (
        <div className="flex flex-wrap gap-1 mt-2">
          {article.tags.slice(0, 3).map((tag, index) => (
            <span
              key={index}
              className="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"
            >
              #{tag}
            </span>
          ))}
          {article.tags.length > 3 && (
            <span className="text-xs text-gray-400">
              +{article.tags.length - 3} more
            </span>
          )}
        </div>
      )}
    </div>
  </div>
);

/**
 * Loading Skeleton Component
 */
const ArticleGridSkeleton = () => (
  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    {[...Array(12)].map((_, index) => (
      <div key={index} className="bg-white rounded-lg shadow-md overflow-hidden animate-pulse">
        <div className="h-48 bg-gray-300"></div>
        <div className="p-4">
          <div className="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
          <div className="h-3 bg-gray-300 rounded w-1/2 mb-2"></div>
          <div className="h-3 bg-gray-300 rounded w-full mb-2"></div>
          <div className="h-3 bg-gray-300 rounded w-5/6"></div>
        </div>
      </div>
    ))}
  </div>
);

/**
 * Error Message Component
 */
const ErrorMessage = ({ error }) => (
  <div className="text-center py-12">
    <div className="bg-red-50 border border-red-200 rounded-lg p-6 max-w-md mx-auto">
      <div className="text-red-600 mb-4">
        <svg className="h-12 w-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
        <h3 className="text-lg font-semibold">Error Loading Articles</h3>
      </div>
      <p className="text-gray-600 mb-4">{error}</p>
      <button
        onClick={() => window.location.reload()}
        className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors"
      >
        Try Again
      </button>
    </div>
  </div>
);

/**
 * Empty State Component
 */
const EmptyState = ({ searchQuery, filters }) => (
  <div className="text-center py-12">
    <div className="max-w-md mx-auto">
      <svg className="h-24 w-24 mx-auto mb-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 className="text-xl font-semibold text-gray-900 mb-2">No Articles Found</h3>
      <p className="text-gray-600 mb-6">
        {searchQuery
          ? `No articles match your search "${searchQuery}"`
          : filters.category || filters.tag
          ? 'No articles match your current filters'
          : 'No articles are available at the moment'}
      </p>
      {(searchQuery || filters.category || filters.tag) && (
        <button
          onClick={() => window.location.href = '/articles'}
          className="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition-colors"
        >
          View All Articles
        </button>
      )}
    </div>
  </div>
);

/**
 * Pagination Component
 */
const Pagination = ({ pagination, onPageChange }) => {
  const { current_page, last_page, from, to, total } = pagination;

  const getPageNumbers = () => {
    const pages = [];
    const showPages = 5;
    let start = Math.max(1, current_page - Math.floor(showPages / 2));
    let end = Math.min(last_page, start + showPages - 1);

    if (end - start + 1 < showPages) {
      start = Math.max(1, end - showPages + 1);
    }

    for (let i = start; i <= end; i++) {
      pages.push(i);
    }

    return pages;
  };

  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handlePageChange = (page) => {
    onPageChange(page);
    scrollToTop();
  };

  return (
    <div className="mt-12 flex flex-col items-center">
      {/* Results Info */}
      <div className="text-sm text-gray-600 mb-4">
        Showing {from} to {to} of {total} results
      </div>

      {/* Pagination Controls */}
      <nav className="flex items-center space-x-2">
        {/* Previous Button */}
        <button
          onClick={() => handlePageChange(current_page - 1)}
          disabled={current_page === 1}
          className={`px-3 py-2 rounded-md text-sm font-medium ${
            current_page === 1
              ? 'text-gray-400 cursor-not-allowed'
              : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'
          }`}
        >
          Previous
        </button>

        {/* First Page */}
        {getPageNumbers()[0] > 1 && (
          <>
            <button
              onClick={() => handlePageChange(1)}
              className="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100"
            >
              1
            </button>
            {getPageNumbers()[0] > 2 && (
              <span className="px-3 py-2 text-gray-400">...</span>
            )}
          </>
        )}

        {/* Page Numbers */}
        {getPageNumbers().map((page) => (
          <button
            key={page}
            onClick={() => handlePageChange(page)}
            className={`px-3 py-2 rounded-md text-sm font-medium ${
              page === current_page
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'
            }`}
          >
            {page}
          </button>
        ))}

        {/* Last Page */}
        {getPageNumbers()[getPageNumbers().length - 1] < last_page && (
          <>
            {getPageNumbers()[getPageNumbers().length - 1] < last_page - 1 && (
              <span className="px-3 py-2 text-gray-400">...</span>
            )}
            <button
              onClick={() => handlePageChange(last_page)}
              className="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-100"
            >
              {last_page}
            </button>
          </>
        )}

        {/* Next Button */}
        <button
          onClick={() => handlePageChange(current_page + 1)}
          disabled={current_page === last_page}
          className={`px-3 py-2 rounded-md text-sm font-medium ${
            current_page === last_page
              ? 'text-gray-400 cursor-not-allowed'
              : 'text-gray-700 hover:text-blue-600 hover:bg-gray-100'
          }`}
        >
          Next
        </button>
      </nav>
    </div>
  );
};

export default ArticlesPage;
