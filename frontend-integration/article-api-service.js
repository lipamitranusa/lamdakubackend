/**
 * Article API Service for React Frontend
 * This service handles all API calls to the Laravel backend article endpoints
 */

class ArticleService {
    constructor(baseURL = 'http://localhost:8000/api/v1') {
        this.baseURL = baseURL;
        this.headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }

    /**
     * Handle API response and errors
     */
    async handleResponse(response) {
        if (!response.ok) {
            const error = await response.json().catch(() => ({ message: 'Network error' }));
            throw new Error(error.message || `HTTP ${response.status}`);
        }
        return response.json();
    }

    /**
     * Get all articles with pagination and filters
     * @param {Object} params - Query parameters
     * @param {number} params.page - Page number
     * @param {number} params.per_page - Items per page
     * @param {string} params.status - Article status filter
     * @param {string} params.category - Category filter
     * @param {string} params.author - Author filter
     * @param {string} params.search - Search query
     * @param {string} params.sort_by - Sort field
     * @param {string} params.sort_order - Sort order (asc/desc)
     * @returns {Promise<Object>} Articles data with pagination
     */
    async getArticles(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = `${this.baseURL}/articles${queryString ? `?${queryString}` : ''}`;
        
        const response = await fetch(url, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get featured articles
     * @param {number} limit - Number of articles to fetch
     * @returns {Promise<Object>} Featured articles
     */
    async getFeaturedArticles(limit = 5) {
        const response = await fetch(`${this.baseURL}/articles/featured?limit=${limit}`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get latest articles
     * @param {number} limit - Number of articles to fetch
     * @returns {Promise<Object>} Latest articles
     */
    async getLatestArticles(limit = 10) {
        const response = await fetch(`${this.baseURL}/articles/latest?limit=${limit}`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get popular articles
     * @param {number} limit - Number of articles to fetch
     * @returns {Promise<Object>} Popular articles
     */
    async getPopularArticles(limit = 10) {
        const response = await fetch(`${this.baseURL}/articles/popular?limit=${limit}`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get single article by slug
     * @param {string} slug - Article slug
     * @returns {Promise<Object>} Article data
     */
    async getArticle(slug) {
        const response = await fetch(`${this.baseURL}/articles/${slug}`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Search articles
     * @param {string} query - Search query
     * @param {Object} filters - Additional filters
     * @returns {Promise<Object>} Search results
     */
    async searchArticles(query, filters = {}) {
        const params = { q: query, ...filters };
        const queryString = new URLSearchParams(params).toString();
        
        const response = await fetch(`${this.baseURL}/articles/search?${queryString}`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get article categories
     * @returns {Promise<Array>} Categories list
     */
    async getCategories() {
        const response = await fetch(`${this.baseURL}/articles/categories`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get article tags
     * @returns {Promise<Array>} Tags list
     */
    async getTags() {
        const response = await fetch(`${this.baseURL}/articles/tags`, {
            method: 'GET',
            headers: this.headers,
        });

        return this.handleResponse(response);
    }

    /**
     * Get articles by category
     * @param {string} category - Category name
     * @param {Object} params - Additional parameters
     * @returns {Promise<Object>} Articles in category
     */
    async getArticlesByCategory(category, params = {}) {
        const queryParams = { category, ...params };
        return this.getArticles(queryParams);
    }

    /**
     * Get articles by tag
     * @param {string} tag - Tag name
     * @param {Object} params - Additional parameters
     * @returns {Promise<Object>} Articles with tag
     */
    async getArticlesByTag(tag, params = {}) {
        const queryParams = { tag, ...params };
        return this.getArticles(queryParams);
    }

    /**
     * Get articles by author
     * @param {string} author - Author name or ID
     * @param {Object} params - Additional parameters
     * @returns {Promise<Object>} Articles by author
     */
    async getArticlesByAuthor(author, params = {}) {
        const queryParams = { author, ...params };
        return this.getArticles(queryParams);
    }
}

// Export for different module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ArticleService;
} else if (typeof window !== 'undefined') {
    window.ArticleService = ArticleService;
}

export default ArticleService;
