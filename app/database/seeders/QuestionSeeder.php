<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\User;
use App\Models\Tag;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure at least one user exists
        $user = User::first() ?: User::factory()->create();

        $titles = [
            "How to fix CORS error in Laravel API?",
            "Best practices for React state management",
            "Why is my Docker container not starting?",
            "How to optimize MySQL queries?",
            "What's the difference between let and const?",
            "How to handle authentication in Next.js?",
            "Python async/await explained",
            "How to deploy Laravel app to production?",
            "Understanding JavaScript closures",
            "Best way to structure a Vue.js project?",
            "How to fix npm dependency conflicts?",
            "PostgreSQL vs MySQL: which to choose?",
            "How to implement JWT authentication?",
            "Understanding Docker networking",
            "How to optimize React performance?",
            "What are Laravel service providers?",
            "How to use Redis for caching?",
            "Understanding Git merge vs rebase",
            "How to handle file uploads in Laravel?",
            "What is dependency injection?",
            "How to test API endpoints?",
            "Understanding REST vs GraphQL",
            "How to secure my web application?",
            "Best practices for error handling",
        ];

        $contents = [
            "I'm getting CORS errors when trying to access my Laravel API from my React frontend. I've tried adding headers but still getting blocked.",
            "What's the best approach for managing state in a large React application? Redux, Context API, or something else?",
            "My Docker container starts then immediately stops. The logs show exit code 0 but I can't figure out what's wrong.",
            "I have a query that takes 5+ seconds to execute. How can I optimize it? Should I add indexes?",
            "I keep seeing both let and const in JavaScript code. When should I use each one?",
            "I need to implement user authentication in my Next.js app. What's the recommended approach?",
            "Can someone explain how async/await works in Python? I'm confused about when to use it.",
            "What are the steps to deploy a Laravel application to a production server? Any best practices?",
            "I don't understand how closures work in JavaScript. Can someone explain with a simple example?",
            "How should I organize components, views, and store in a large Vue.js application?",
            "Running npm install gives me conflicts between package versions. How do I resolve this?",
            "I'm starting a new project and can't decide between PostgreSQL and MySQL. What are the pros and cons?",
            "How do I implement JWT token-based authentication in my REST API? Security best practices?",
            "How does Docker container networking work? I can't get my containers to communicate.",
            "My React app is getting slow with large lists. What are some performance optimization techniques?",
            "What exactly are service providers in Laravel and when should I create one?",
            "How can I use Redis to cache database queries in my application? Any examples?",
            "What's the difference between git merge and git rebase? When should I use each?",
            "What's the best way to handle file uploads in Laravel? I need to validate file types and sizes.",
            "I keep hearing about dependency injection but don't really understand what it is or why it's useful.",
            "What's the best approach for testing API endpoints? Should I use Postman or write automated tests?",
            "What are the main differences between REST and GraphQL? Which should I use for my new project?",
            "What are the most important security measures I should implement in my web application?",
            "What are best practices for handling errors in a Node.js/Express application?",
        ];

        // All unique tag names
        $tagNames = [
            "laravel",
            "redis",
            "api",
            "cors",
            "react",
            "state-management",
            "redux",
            "docker",
            "containers",
            "debugging",
            "mysql",
            "optimization",
            "performance",
            "javascript",
            "fundamentals",
            "nextjs",
            "authentication",
            "jwt",
            "python",
            "async",
            "concurrency",
            "deployment",
            "production",
            "closures",
            "vuejs",
            "architecture",
            "best-practices",
            "npm",
            "dependencies",
            "troubleshooting",
            "postgresql",
            "database",
            "security",
            "web-development",
            "performance",
            "optimization",
            "service-providers",
            "caching",
            "git",
            "version-control",
            "file-upload",
            "validation",
            "design-patterns",
            "dependency-injection",
            "testing",
            "automation",
            "rest",
            "graphql",
            "api-design",
            "nodejs",
            "error-handling",
            "express",
            "networking"
        ];

        // Preload tags into name => id mapping
        $tags = [];
        foreach ($tagNames as $name) {
            $tagModel = Tag::firstOrCreate(['name' => $name]);
            $tags[$name] = $tagModel->id;
        }

        // Question-to-tags mapping
        $tagsList = [
            ["laravel", "api", "cors"],
            ["react", "state-management", "redux"],
            ["docker", "containers", "debugging"],
            ["mysql", "optimization", "performance"],
            ["javascript", "fundamentals"],
            ["nextjs", "authentication", "jwt"],
            ["python", "async", "concurrency"],
            ["laravel", "deployment", "production"],
            ["javascript", "closures"],
            ["vuejs", "architecture", "best-practices"],
            ["npm", "dependencies", "troubleshooting"],
            ["postgresql", "mysql", "database"],
            ["jwt", "authentication", "security"],
            ["docker", "networking"],
            ["react", "performance", "optimization"],
            ["laravel", "service-providers"],
            ["redis", "caching", "performance"],
            ["git", "version-control"],
            ["laravel", "file-upload", "validation"],
            ["design-patterns", "dependency-injection"],
            ["api", "testing", "automation"],
            ["rest", "graphql", "api-design"],
            ["security", "web-development"],
            ["nodejs", "error-handling", "express"],
        ];

        // Create questions and attach tags by preloaded IDs
        for ($i = 0; $i < count($titles); $i++) {
            $question = Question::create([
                'title' => $titles[$i],
                'content' => $contents[$i],
                'user_id' => $user->id,
            ]);

            foreach ($tagsList[$i] as $tagName) {
                $question->tags()->attach($tags[$tagName]);
            }
        }
    }
}
