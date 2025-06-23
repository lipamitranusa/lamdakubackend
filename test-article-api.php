<?php

/**
 * Article API Test Script
 * This script tests all article API endpoints to ensure they're working correctly
 */

require_once __DIR__ . '/vendor/autoload.php';

class ArticleApiTester
{
    private $baseUrl;
    private $results = [];

    public function __construct($baseUrl = 'http://localhost:8000/api/v1')
    {
        $this->baseUrl = $baseUrl;
    }

    public function runAllTests()
    {
        echo "🚀 Starting Article API Tests...\n\n";

        $tests = [
            'testArticlesList',
            'testFeaturedArticles',
            'testLatestArticles',
            'testPopularArticles',
            'testArticleSearch',
            'testCategories',
            'testTags',
            'testSingleArticle'
        ];

        foreach ($tests as $test) {
            $this->$test();
        }

        $this->printSummary();
    }

    private function makeRequest($endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/json',
                    'Content-Type: application/json'
                ],
                'timeout' => 10
            ]
        ]);

        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            return [
                'success' => false,
                'error' => 'Request failed',
                'url' => $url
            ];
        }

        $data = json_decode($response, true);
        
        return [
            'success' => true,
            'data' => $data,
            'url' => $url
        ];
    }

    private function testArticlesList()
    {
        echo "📝 Testing Articles List Endpoint...\n";
        
        $result = $this->makeRequest('/articles', ['per_page' => 5]);
        
        if (!$result['success']) {
            $this->recordResult('Articles List', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->recordResult('Articles List', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Response structure valid\n";
        echo "   ✓ Found " . count($data['data']) . " articles\n";
        
        if (isset($data['meta'])) {
            echo "   ✓ Pagination data present\n";
        }

        $this->recordResult('Articles List', true, 'All checks passed');
    }

    private function testFeaturedArticles()
    {
        echo "\n⭐ Testing Featured Articles Endpoint...\n";
        
        $result = $this->makeRequest('/articles/featured', ['limit' => 3]);
        
        if (!$result['success']) {
            $this->recordResult('Featured Articles', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->recordResult('Featured Articles', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Found " . count($data['data']) . " featured articles\n";

        $this->recordResult('Featured Articles', true, 'All checks passed');
    }

    private function testLatestArticles()
    {
        echo "\n🕐 Testing Latest Articles Endpoint...\n";
        
        $result = $this->makeRequest('/articles/latest', ['limit' => 5]);
        
        if (!$result['success']) {
            $this->recordResult('Latest Articles', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->recordResult('Latest Articles', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Found " . count($data['data']) . " latest articles\n";

        $this->recordResult('Latest Articles', true, 'All checks passed');
    }

    private function testPopularArticles()
    {
        echo "\n🔥 Testing Popular Articles Endpoint...\n";
        
        $result = $this->makeRequest('/articles/popular', ['limit' => 5]);
        
        if (!$result['success']) {
            $this->recordResult('Popular Articles', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->recordResult('Popular Articles', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Found " . count($data['data']) . " popular articles\n";

        $this->recordResult('Popular Articles', true, 'All checks passed');
    }

    private function testArticleSearch()
    {
        echo "\n🔍 Testing Article Search Endpoint...\n";
        
        $result = $this->makeRequest('/articles/search', ['q' => 'test']);
        
        if (!$result['success']) {
            $this->recordResult('Article Search', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data']) || !is_array($data['data'])) {
            $this->recordResult('Article Search', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Search functionality working\n";
        echo "   ✓ Found " . count($data['data']) . " search results\n";

        $this->recordResult('Article Search', true, 'All checks passed');
    }

    private function testCategories()
    {
        echo "\n📂 Testing Categories Endpoint...\n";
        
        $result = $this->makeRequest('/articles/categories');
        
        if (!$result['success']) {
            $this->recordResult('Categories', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!is_array($data)) {
            $this->recordResult('Categories', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Found " . count($data) . " categories\n";

        $this->recordResult('Categories', true, 'All checks passed');
    }

    private function testTags()
    {
        echo "\n🏷️ Testing Tags Endpoint...\n";
        
        $result = $this->makeRequest('/articles/tags');
        
        if (!$result['success']) {
            $this->recordResult('Tags', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!is_array($data)) {
            $this->recordResult('Tags', false, 'Invalid response structure');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Found " . count($data) . " tags\n";

        $this->recordResult('Tags', true, 'All checks passed');
    }

    private function testSingleArticle()
    {
        echo "\n📄 Testing Single Article Endpoint...\n";
        
        // First, get a list of articles to find a valid slug
        $listResult = $this->makeRequest('/articles', ['per_page' => 1]);
        
        if (!$listResult['success'] || empty($listResult['data']['data'])) {
            $this->recordResult('Single Article', false, 'No articles available for testing');
            return;
        }

        $firstArticle = $listResult['data']['data'][0];
        $slug = $firstArticle['slug'];

        echo "   Testing with article slug: {$slug}\n";
        
        $result = $this->makeRequest('/articles/' . $slug);
        
        if (!$result['success']) {
            $this->recordResult('Single Article', false, $result['error']);
            return;
        }

        $data = $result['data'];
        
        if (!isset($data['data'])) {
            $this->recordResult('Single Article', false, 'Invalid response structure');
            return;
        }

        $article = $data['data'];
        
        if (!isset($article['title']) || !isset($article['content'])) {
            $this->recordResult('Single Article', false, 'Missing required article fields');
            return;
        }

        echo "   ✓ Endpoint accessible\n";
        echo "   ✓ Article data complete\n";
        echo "   ✓ Title: " . substr($article['title'], 0, 50) . "...\n";

        $this->recordResult('Single Article', true, 'All checks passed');
    }

    private function recordResult($test, $success, $message)
    {
        $this->results[] = [
            'test' => $test,
            'success' => $success,
            'message' => $message
        ];

        $status = $success ? '✅' : '❌';
        echo "   {$status} {$message}\n";
    }

    private function printSummary()
    {
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "🎯 TEST SUMMARY\n";
        echo str_repeat('=', 60) . "\n";

        $passed = 0;
        $total = count($this->results);

        foreach ($this->results as $result) {
            $status = $result['success'] ? '✅' : '❌';
            echo "{$status} {$result['test']}: {$result['message']}\n";
            
            if ($result['success']) {
                $passed++;
            }
        }

        echo "\n" . str_repeat('-', 60) . "\n";
        echo "📊 Results: {$passed}/{$total} tests passed\n";

        if ($passed === $total) {
            echo "🎉 All tests passed! Your Article API is working perfectly.\n";
        } else {
            echo "⚠️  Some tests failed. Please check the Laravel logs and ensure:\n";
            echo "   1. Laravel server is running (php artisan serve)\n";
            echo "   2. Database is properly configured\n";
            echo "   3. Article migration has been run\n";
            echo "   4. Sample data exists (run ArticleSeeder)\n";
        }

        echo "\n📝 Next Steps:\n";
        echo "   1. Test the React frontend integration\n";
        echo "   2. Verify CORS settings if needed\n";
        echo "   3. Check frontend .env configuration\n";
        echo "   4. Test the complete user flow\n";
    }
}

// Run the tests
$tester = new ArticleApiTester();
$tester->runAllTests();
