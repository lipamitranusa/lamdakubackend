import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';
import { FeaturedArticles, LatestArticles, ArticleDetail } from './components/Articles/ArticleComponents';
import { ArticlesPage } from './components/ArticlesPage';

/**
 * Complete Example App with Article Integration
 * Copy this to your main App.js or create separate pages
 */

// Homepage Component
const HomePage = () => {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <Link to="/" className="text-xl font-bold text-gray-900">
              Your Company
            </Link>
            <div className="flex space-x-4">
              <Link to="/" className="text-gray-600 hover:text-gray-900">
                Home
              </Link>
              <Link to="/articles" className="text-gray-600 hover:text-gray-900">
                Articles
              </Link>
              <Link to="/about" className="text-gray-600 hover:text-gray-900">
                About
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6">
            Welcome to Our Platform
          </h1>
          <p className="text-xl md:text-2xl mb-8">
            Discover insights, tutorials, and updates from our team
          </p>
          <Link
            to="/articles"
            className="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
          >
            Explore Articles
          </Link>
        </div>
      </section>

      {/* Featured Articles Section */}
      <FeaturedArticles limit={3} />

      {/* Latest Articles Section */}
      <LatestArticles limit={6} excludeIds={[]} />

      {/* CTA Section */}
      <section className="bg-gray-900 text-white py-16">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">Stay Updated</h2>
          <p className="text-xl mb-8">
            Get the latest articles and insights delivered to your inbox
          </p>
          <div className="flex justify-center">
            <div className="flex max-w-md">
              <input
                type="email"
                placeholder="Enter your email"
                className="flex-1 px-4 py-2 rounded-l-lg text-gray-900 focus:outline-none"
              />
              <button className="bg-blue-600 px-6 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
                Subscribe
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
};

// Article Page Component (single article)
const ArticlePage = () => {
  return (
    <div className="min-h-screen bg-white">
      {/* Navigation */}
      <nav className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <Link to="/" className="text-xl font-bold text-gray-900">
              Your Company
            </Link>
            <div className="flex space-x-4">
              <Link to="/" className="text-gray-600 hover:text-gray-900">
                Home
              </Link>
              <Link to="/articles" className="text-gray-600 hover:text-gray-900">
                Articles
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Article Content */}
      <ArticleDetail />
    </div>
  );
};

// Articles Listing Page Component
const ArticlesListPage = () => {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <Link to="/" className="text-xl font-bold text-gray-900">
              Your Company
            </Link>
            <div className="flex space-x-4">
              <Link to="/" className="text-gray-600 hover:text-gray-900">
                Home
              </Link>
              <Link to="/articles" className="text-blue-600 font-semibold">
                Articles
              </Link>
            </div>
          </div>
        </div>
      </nav>

      {/* Articles Page Content */}
      <ArticlesPage />
    </div>
  );
};

// About Page Component (example)
const AboutPage = () => {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Navigation */}
      <nav className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4">
          <div className="flex items-center justify-between h-16">
            <Link to="/" className="text-xl font-bold text-gray-900">
              Your Company
            </Link>
            <div className="flex space-x-4">
              <Link to="/" className="text-gray-600 hover:text-gray-900">
                Home
              </Link>
              <Link to="/articles" className="text-gray-600 hover:text-gray-900">
                Articles
              </Link>
              <Link to="/about" className="text-blue-600 font-semibold">
                About
              </Link>
            </div>
          </div>
        </div>
      </nav>

      <div className="container mx-auto px-4 py-12">
        <h1 className="text-3xl font-bold mb-6">About Us</h1>
        <p className="text-gray-600 mb-4">
          This is an example about page. You can customize this content according to your needs.
        </p>
        <p className="text-gray-600">
          Our article management system is now fully integrated and ready to use!
        </p>
      </div>
    </div>
  );
};

// Main App Component
const App = () => {
  return (
    <Router>
      <div className="App">
        <Routes>
          <Route path="/" element={<HomePage />} />
          <Route path="/articles" element={<ArticlesListPage />} />
          <Route path="/articles/:slug" element={<ArticlePage />} />
          <Route path="/about" element={<AboutPage />} />
        </Routes>
      </div>
    </Router>
  );
};

export default App;

/**
 * Usage Instructions:
 * 
 * 1. Install required dependencies:
 *    npm install react-router-dom axios
 * 
 * 2. Replace your src/App.js with this code, or create separate page components
 * 
 * 3. Ensure you have copied all the integration files:
 *    - src/services/article-api-service.js
 *    - src/components/Articles/ArticleComponents.jsx
 *    - src/hooks/useArticles.js
 *    - src/utils/articleHelpers.js
 *    - src/config/api.js
 * 
 * 4. Update your .env file:
 *    REACT_APP_API_BASE_URL=http://localhost:8000/api/v1
 *    REACT_APP_BACKEND_URL=http://localhost:8000
 * 
 * 5. Start your React development server:
 *    npm start
 * 
 * Features included:
 * - Homepage with featured and latest articles
 * - Complete articles listing with filters, search, and pagination
 * - Individual article pages with full content
 * - Responsive design
 * - SEO-friendly URLs
 * - Error handling and loading states
 * - Navigation between pages
 * 
 * Customization:
 * - Update the company name and branding
 * - Modify the color scheme in Tailwind classes
 * - Add your own header/footer components
 * - Integrate with your existing navigation
 * - Add authentication if needed
 * - Customize the article display format
 */
