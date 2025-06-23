# Article Management System - Frontend Integration Guide

This guide helps you integrate the Laravel backend article management system with your React frontend at `D:\laragon\www\LAMDAKU\accreditation-company-profile`.

## Prerequisites

1. **Backend Setup**: Laravel backend should be running at `http://localhost:8000`
2. **Frontend Setup**: React frontend should be at `http://localhost:3000`
3. **API Routes**: Article API routes are registered and accessible

## Files Overview

### 1. Core Service
- `article-api-service.js` - Main API service for all article operations
- `ArticleComponents.jsx` - Complete React components for article display

### 2. Integration Files (Copy to your React project)
- `hooks/useArticles.js` - Custom React hooks for article data
- `components/Articles/` - Modular article components
- `utils/articleHelpers.js` - Utility functions
- `config/api.js` - API configuration

## Quick Integration Steps

### Step 1: Copy Files to React Project
Copy these files to your React project directory:

```bash
# From: d:\laragon\www\LAMDAKU\lamdaku-cms-backend\frontend-integration\
# To: D:\laragon\www\LAMDAKU\accreditation-company-profile\src\

# Copy the service
cp article-api-service.js /src/services/

# Copy components
cp ArticleComponents.jsx /src/components/Articles/

# Copy integration files (create these directories if needed)
cp hooks/* /src/hooks/
cp utils/* /src/utils/
cp config/* /src/config/
```

### Step 2: Install Dependencies
```bash
cd D:\laragon\www\LAMDAKU\accreditation-company-profile
npm install axios
```

### Step 3: Update Your API Configuration
Create or update `src/config/api.js`:
```javascript
export const API_BASE_URL = 'http://localhost:8000/api/v1';
export const BACKEND_URL = 'http://localhost:8000';
```

### Step 4: Basic Usage Examples

#### Display Featured Articles on Homepage
```jsx
import { FeaturedArticles } from '../components/Articles/ArticleComponents';

function HomePage() {
  return (
    <div>
      <FeaturedArticles limit={3} />
      {/* Your other homepage content */}
    </div>
  );
}
```

#### Create Articles Listing Page
```jsx
import { ArticlesList } from '../components/Articles/ArticleComponents';

function ArticlesPage() {
  return (
    <div>
      <h1>Our Articles</h1>
      <ArticlesList itemsPerPage={9} />
    </div>
  );
}
```

#### Single Article Page
```jsx
import { useParams } from 'react-router-dom';
import { ArticleDetail } from '../components/Articles/ArticleComponents';

function ArticlePage() {
  const { slug } = useParams();
  
  return <ArticleDetail slug={slug} />;
}
```

### Step 5: Add Routes (React Router)
```jsx
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import ArticlesPage from './pages/ArticlesPage';
import ArticlePage from './pages/ArticlePage';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/articles" element={<ArticlesPage />} />
        <Route path="/articles/:slug" element={<ArticlePage />} />
        {/* Your other routes */}
      </Routes>
    </Router>
  );
}
```

## Component Documentation

### Available Components

1. **ArticleCard** - Single article display card
2. **FeaturedArticles** - Grid of featured articles
3. **LatestArticles** - Grid of latest articles  
4. **ArticleDetail** - Full article page with content
5. **ArticlesList** - Paginated articles listing with filters
6. **ArticleSearch** - Search component with suggestions

### Component Props

#### FeaturedArticles
```jsx
<FeaturedArticles 
  limit={3}           // Number of articles to show
/>
```

#### LatestArticles  
```jsx
<LatestArticles 
  limit={6}           // Number of articles to show
  excludeIds={[1,2]}  // Article IDs to exclude
/>
```

#### ArticlesList
```jsx
<ArticlesList 
  category="news"     // Filter by category
  tag="react"         // Filter by tag
  author="john"       // Filter by author
  search="tutorial"   // Search query
  itemsPerPage={9}    // Articles per page
/>
```

#### ArticleDetail
```jsx
<ArticleDetail 
  slug="my-article"   // Article slug from URL
/>
```

## API Endpoints Used

The components use these backend API endpoints:

- `GET /api/v1/articles` - List articles with pagination
- `GET /api/v1/articles/featured` - Get featured articles
- `GET /api/v1/articles/latest` - Get latest articles
- `GET /api/v1/articles/popular` - Get popular articles  
- `GET /api/v1/articles/search` - Search articles
- `GET /api/v1/articles/{slug}` - Get single article
- `GET /api/v1/articles/categories` - Get categories
- `GET /api/v1/articles/tags` - Get tags

## Styling

The components use Tailwind CSS classes. If you're using a different CSS framework:

1. Replace Tailwind classes with your framework's classes
2. Create custom CSS classes to match the styling
3. Use CSS-in-JS solutions like styled-components

## Customization

### Custom Styling
```jsx
// Override default classes
<FeaturedArticles 
  className="custom-featured-grid"
  cardClassName="custom-article-card"
/>
```

### Custom Image Handling
```jsx
// In article-api-service.js, modify getImageUrl function
const getImageUrl = (imagePath) => {
  if (!imagePath) return '/images/default-article.jpg';
  return imagePath.startsWith('http') 
    ? imagePath 
    : `${BACKEND_URL}/storage/${imagePath}`;
};
```

### Error Handling
```jsx
// Custom error component
const ArticleError = ({ error, retry }) => (
  <div className="text-center py-8">
    <p className="text-red-600 mb-4">Error: {error}</p>
    <button onClick={retry} className="btn btn-primary">
      Try Again
    </button>
  </div>
);
```

## Advanced Usage

### Custom Hooks
```jsx
import { useArticles } from '../hooks/useArticles';

function CustomArticleComponent() {
  const { 
    articles, 
    loading, 
    error, 
    fetchMore, 
    refresh 
  } = useArticles({ 
    category: 'news',
    limit: 10 
  });

  // Your custom component logic
}
```

### SEO Integration
```jsx
import { Helmet } from 'react-helmet';

function ArticlePage({ article }) {
  return (
    <>
      <Helmet>
        <title>{article.meta_title || article.title}</title>
        <meta name="description" content={article.meta_description} />
        <meta property="og:title" content={article.og_title} />
        <meta property="og:description" content={article.og_description} />
        <meta property="og:image" content={article.og_image} />
      </Helmet>
      <ArticleDetail slug={article.slug} />
    </>
  );
}
```

## Troubleshooting

### Common Issues

1. **CORS Errors**: Ensure Laravel backend has CORS configured for your frontend domain
2. **Image Loading**: Check if storage link is created: `php artisan storage:link`
3. **API Not Found**: Verify article routes are registered in `routes/api.php`
4. **Empty Results**: Check if articles exist and are published in backend

### Debug Mode
```javascript
// Enable debug mode in article-api-service.js
const articleService = new ArticleService('http://localhost:8000/api/v1', {
  debug: true
});
```

## Performance Optimization

1. **Lazy Loading**: Implement lazy loading for article images
2. **Caching**: Add browser caching for API responses
3. **Pagination**: Use pagination for large article lists
4. **Image Optimization**: Optimize images in backend

## Security Considerations

1. **XSS Protection**: Sanitize article content before rendering
2. **Input Validation**: Validate search queries and filters
3. **API Rate Limiting**: Implement rate limiting if needed

## Testing

Create test files for components:
```javascript
// ArticleComponents.test.jsx
import { render, screen } from '@testing-library/react';
import { FeaturedArticles } from './ArticleComponents';

test('renders featured articles', async () => {
  render(<FeaturedArticles />);
  // Your test assertions
});
```

## Support

For issues and questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify API responses in Network tab
4. Test API endpoints directly with Postman/curl

## Next Steps

1. Implement comment system
2. Add article sharing functionality  
3. Create admin interface for article management
4. Add article analytics and tracking
5. Implement article caching system
