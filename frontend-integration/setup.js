/**
 * Setup script to copy integration files to React frontend
 * Run this script to automatically copy all integration files to your React project
 */

const fs = require('fs');
const path = require('path');

// Configuration
const FRONTEND_PATH = 'D:\\laragon\\www\\LAMDAKU\\accreditation-company-profile';
const BACKEND_INTEGRATION_PATH = 'd:\\laragon\\www\\LAMDAKU\\lamdaku-cms-backend\\frontend-integration';

// File mappings (source -> destination)
const FILE_MAPPINGS = {
  // Core service
  'article-api-service.js': 'src/services/article-api-service.js',
  
  // React components
  'ArticleComponents.jsx': 'src/components/Articles/ArticleComponents.jsx',
  
  // Custom hooks
  'hooks/useArticles.js': 'src/hooks/useArticles.js',
  
  // Utilities
  'utils/articleHelpers.js': 'src/utils/articleHelpers.js',
  
  // Configuration
  'config/api.js': 'src/config/api.js'
};

// Directory mappings
const DIRECTORY_MAPPINGS = {
  'components': 'src/components/Articles',
  'hooks': 'src/hooks',
  'utils': 'src/utils',
  'config': 'src/config',
  'services': 'src/services'
};

/**
 * Ensure directory exists
 */
function ensureDirectoryExists(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
    console.log(`✓ Created directory: ${dirPath}`);
  }
}

/**
 * Copy file with error handling
 */
function copyFile(source, destination) {
  try {
    const sourceFile = path.join(BACKEND_INTEGRATION_PATH, source);
    const destFile = path.join(FRONTEND_PATH, destination);
    
    // Ensure destination directory exists
    ensureDirectoryExists(path.dirname(destFile));
    
    // Copy file
    fs.copyFileSync(sourceFile, destFile);
    console.log(`✓ Copied: ${source} -> ${destination}`);
    return true;
  } catch (error) {
    console.error(`✗ Failed to copy ${source}: ${error.message}`);
    return false;
  }
}

/**
 * Check if frontend project exists
 */
function checkFrontendExists() {
  if (!fs.existsSync(FRONTEND_PATH)) {
    console.error(`✗ Frontend project not found at: ${FRONTEND_PATH}`);
    console.log('Please update FRONTEND_PATH in this script to point to your React project.');
    return false;
  }
  
  const packageJsonPath = path.join(FRONTEND_PATH, 'package.json');
  if (!fs.existsSync(packageJsonPath)) {
    console.error(`✗ package.json not found in: ${FRONTEND_PATH}`);
    console.log('This doesn\'t appear to be a valid React project.');
    return false;
  }
  
  return true;
}

/**
 * Check if backend integration files exist
 */
function checkBackendFiles() {
  if (!fs.existsSync(BACKEND_INTEGRATION_PATH)) {
    console.error(`✗ Backend integration path not found: ${BACKEND_INTEGRATION_PATH}`);
    return false;
  }
  
  const missingFiles = [];
  Object.keys(FILE_MAPPINGS).forEach(file => {
    const filePath = path.join(BACKEND_INTEGRATION_PATH, file);
    if (!fs.existsSync(filePath)) {
      missingFiles.push(file);
    }
  });
  
  if (missingFiles.length > 0) {
    console.error('✗ Missing backend integration files:');
    missingFiles.forEach(file => console.error(`  - ${file}`));
    return false;
  }
  
  return true;
}

/**
 * Create environment file if it doesn't exist
 */
function createEnvironmentFile() {
  const envPath = path.join(FRONTEND_PATH, '.env');
  const envExamplePath = path.join(FRONTEND_PATH, '.env.example');
  
  if (!fs.existsSync(envPath)) {
    const envContent = `# Article Management API Configuration
REACT_APP_API_BASE_URL=http://localhost:8000/api/v1
REACT_APP_BACKEND_URL=http://localhost:8000

# Feature Flags
REACT_APP_ENABLE_COMMENTS=false
REACT_APP_ENABLE_SHARING=true
REACT_APP_ENABLE_FAVORITES=true
REACT_APP_ENABLE_READING_TIME=true
REACT_APP_ENABLE_VIEW_COUNT=true
REACT_APP_ENABLE_RELATED=true
REACT_APP_ENABLE_SEARCH_SUGGESTIONS=true
`;
    
    fs.writeFileSync(envPath, envContent);
    console.log('✓ Created .env file with API configuration');
  }
  
  if (!fs.existsSync(envExamplePath)) {
    fs.copyFileSync(envPath, envExamplePath);
    console.log('✓ Created .env.example file');
  }
}

/**
 * Update package.json dependencies
 */
function updatePackageJson() {
  const packageJsonPath = path.join(FRONTEND_PATH, 'package.json');
  
  try {
    const packageJson = JSON.parse(fs.readFileSync(packageJsonPath, 'utf8'));
    
    const requiredDependencies = {
      'axios': '^1.6.0',
      'react-router-dom': '^6.8.0'
    };
    
    let needsUpdate = false;
    const missingDeps = [];
    
    Object.entries(requiredDependencies).forEach(([dep, version]) => {
      if (!packageJson.dependencies?.[dep] && !packageJson.devDependencies?.[dep]) {
        missingDeps.push(`${dep}@${version}`);
        needsUpdate = true;
      }
    });
    
    if (needsUpdate) {
      console.log('ℹ Missing required dependencies:');
      missingDeps.forEach(dep => console.log(`  - ${dep}`));
      console.log('Run: npm install ' + missingDeps.join(' '));
    } else {
      console.log('✓ All required dependencies are present');
    }
    
  } catch (error) {
    console.error(`✗ Failed to read package.json: ${error.message}`);
  }
}

/**
 * Create example usage files
 */
function createExampleFiles() {
  // Create example page component
  const examplePageContent = `import React from 'react';
import { FeaturedArticles, LatestArticles } from '../components/Articles/ArticleComponents';

/**
 * Example Homepage with Articles
 */
function HomePage() {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Hero Section */}
      <section className="bg-blue-600 text-white py-20">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl font-bold mb-4">Welcome to Our Blog</h1>
          <p className="text-xl">Discover our latest articles and insights</p>
        </div>
      </section>

      {/* Featured Articles */}
      <FeaturedArticles limit={3} />

      {/* Latest Articles */}
      <LatestArticles limit={6} excludeIds={[]} />
    </div>
  );
}

export default HomePage;
`;
  
  const examplePagePath = path.join(FRONTEND_PATH, 'src/pages/HomePage.example.jsx');
  ensureDirectoryExists(path.dirname(examplePagePath));
  
  if (!fs.existsSync(examplePagePath)) {
    fs.writeFileSync(examplePagePath, examplePageContent);
    console.log('✓ Created example HomePage component');
  }

  // Create example articles page
  const exampleArticlesPageContent = `import React from 'react';
import { useParams, useSearchParams } from 'react-router-dom';
import { ArticlesList } from '../components/Articles/ArticleComponents';

/**
 * Example Articles Listing Page
 */
function ArticlesPage() {
  const [searchParams] = useSearchParams();
  
  const filters = {
    category: searchParams.get('category') || '',
    tag: searchParams.get('tag') || '',
    author: searchParams.get('author') || '',
    search: searchParams.get('search') || ''
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold mb-8">Articles</h1>
        <ArticlesList 
          category={filters.category}
          tag={filters.tag}
          author={filters.author}
          search={filters.search}
          itemsPerPage={9}
        />
      </div>
    </div>
  );
}

export default ArticlesPage;
`;
  
  const exampleArticlesPagePath = path.join(FRONTEND_PATH, 'src/pages/ArticlesPage.example.jsx');
  
  if (!fs.existsSync(exampleArticlesPagePath)) {
    fs.writeFileSync(exampleArticlesPagePath, exampleArticlesPageContent);
    console.log('✓ Created example ArticlesPage component');
  }

  // Create example article detail page
  const exampleArticleDetailContent = `import React from 'react';
import { useParams } from 'react-router-dom';
import { ArticleDetail } from '../components/Articles/ArticleComponents';

/**
 * Example Article Detail Page
 */
function ArticlePage() {
  const { slug } = useParams();

  return (
    <div className="min-h-screen bg-white">
      <ArticleDetail slug={slug} />
    </div>
  );
}

export default ArticlePage;
`;
  
  const exampleArticleDetailPath = path.join(FRONTEND_PATH, 'src/pages/ArticlePage.example.jsx');
  
  if (!fs.existsSync(exampleArticleDetailPath)) {
    fs.writeFileSync(exampleArticleDetailPath, exampleArticleDetailContent);
    console.log('✓ Created example ArticlePage component');
  }
}

/**
 * Create README for frontend integration
 */
function createFrontendReadme() {
  const readmeContent = `# Article Management Integration

This project has been integrated with the Laravel backend article management system.

## Files Added

### Services
- \`src/services/article-api-service.js\` - Main API service for article operations

### Components  
- \`src/components/Articles/ArticleComponents.jsx\` - React components for article display

### Hooks
- \`src/hooks/useArticles.js\` - Custom React hooks for article data management

### Utils
- \`src/utils/articleHelpers.js\` - Utility functions for article formatting and processing

### Config
- \`src/config/api.js\` - API configuration and constants

### Example Pages
- \`src/pages/HomePage.example.jsx\` - Example homepage with articles
- \`src/pages/ArticlesPage.example.jsx\` - Example articles listing page  
- \`src/pages/ArticlePage.example.jsx\` - Example article detail page

## Quick Start

1. **Install Dependencies**:
   \`\`\`bash
   npm install axios react-router-dom
   \`\`\`

2. **Configure Environment**:
   Update \`.env\` file with your API URLs (already created)

3. **Add Routes**:
   \`\`\`jsx
   import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
   import ArticlesPage from './pages/ArticlesPage';
   import ArticlePage from './pages/ArticlePage';

   function App() {
     return (
       <Router>
         <Routes>
           <Route path="/articles" element={<ArticlesPage />} />
           <Route path="/articles/:slug" element={<ArticlePage />} />
         </Routes>
       </Router>
     );
   }
   \`\`\`

4. **Use Components**:
   \`\`\`jsx
   import { FeaturedArticles } from './components/Articles/ArticleComponents';

   function HomePage() {
     return (
       <div>
         <FeaturedArticles limit={3} />
       </div>
     );
   }
   \`\`\`

## Available Components

- **FeaturedArticles** - Display featured articles
- **LatestArticles** - Display latest articles  
- **ArticlesList** - Full articles listing with pagination and filters
- **ArticleDetail** - Single article page with full content
- **ArticleCard** - Individual article card component
- **ArticleSearch** - Search component with suggestions

## Backend Requirements

Ensure your Laravel backend is running at \`http://localhost:8000\` with the article management system installed.

For detailed documentation, see the integration README in the backend project.
`;
  
  const readmePath = path.join(FRONTEND_PATH, 'ARTICLE_INTEGRATION.md');
  
  if (!fs.existsSync(readmePath)) {
    fs.writeFileSync(readmePath, readmeContent);
    console.log('✓ Created integration documentation');
  }
}

/**
 * Main setup function
 */
function main() {
  console.log('🚀 Starting Article Management Integration Setup...\n');
  
  // Check prerequisites
  if (!checkFrontendExists()) {
    process.exit(1);
  }
  
  if (!checkBackendFiles()) {
    process.exit(1);
  }
  
  console.log('✓ Prerequisites check passed\n');
  
  // Create required directories
  console.log('📁 Creating directories...');
  Object.values(DIRECTORY_MAPPINGS).forEach(dir => {
    ensureDirectoryExists(path.join(FRONTEND_PATH, dir));
  });
  
  // Copy integration files
  console.log('\n📋 Copying integration files...');
  let successCount = 0;
  let totalCount = Object.keys(FILE_MAPPINGS).length;
  
  Object.entries(FILE_MAPPINGS).forEach(([source, destination]) => {
    if (copyFile(source, destination)) {
      successCount++;
    }
  });
  
  // Create additional files
  console.log('\n⚙️ Setting up configuration...');
  createEnvironmentFile();
  updatePackageJson();
  
  console.log('\n📝 Creating example files...');
  createExampleFiles();
  createFrontendReadme();
  
  // Summary
  console.log('\n' + '='.repeat(50));
  console.log('🎉 Setup Complete!');
  console.log('='.repeat(50));
  console.log(`✓ Copied ${successCount}/${totalCount} files successfully`);
  console.log(`✓ Frontend project: ${FRONTEND_PATH}`);
  console.log(`✓ Integration files ready`);
  
  if (successCount === totalCount) {
    console.log('\n🟢 All files copied successfully!');
    console.log('\nNext steps:');
    console.log('1. cd ' + FRONTEND_PATH);
    console.log('2. npm install axios react-router-dom');
    console.log('3. Start your React development server');
    console.log('4. Check the example files for usage patterns');
    console.log('5. Read ARTICLE_INTEGRATION.md for detailed documentation');
  } else {
    console.log('\n🟡 Some files failed to copy. Please check the errors above.');
  }
  
  console.log('\n📚 Documentation:');
  console.log('- Backend: frontend-integration/README.md');
  console.log('- Frontend: ARTICLE_INTEGRATION.md');
}

// Run the setup
if (require.main === module) {
  main();
}

module.exports = {
  main,
  FRONTEND_PATH,
  BACKEND_INTEGRATION_PATH,
  FILE_MAPPINGS
};
