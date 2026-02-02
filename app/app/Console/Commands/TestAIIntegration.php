<?php

namespace App\Console\Commands;

use App\Services\AI\AIManager;
use Illuminate\Console\Command;

class TestAIIntegration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test {question?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test AI integration by generating an answer to a question';

    /**
     * Execute the console command.
     */
    public function handle(AIManager $aiManager)
    {
        $this->info('🤖 AI Integration Test');
        $this->newLine();

        // Check if any provider is available
        if (!$aiManager->isAnyProviderAvailable()) {
            $this->error('❌ No AI providers are configured or available.');
            $this->info('Please set up at least one provider in your .env file:');
            $this->line('  - OPENAI_API_KEY');
            $this->line('  - ANTHROPIC_API_KEY');
            $this->line('  - GEMINI_API_KEY');
            $this->line('  - OPENROUTER_API_KEY');
            return 1;
        }

        $availableProviders = $aiManager->getAvailableProviders();
        $this->info('✅ Available providers: ' . implode(', ', array_map(
            fn($p) => $p->getProviderName(),
            $availableProviders
        )));
        $this->newLine();

        // Get question from argument or ask
        $question = $this->argument('question') ??
            $this->ask('What question should I ask the AI?', 'What is Laravel?');

        $this->info("Generating answer for: \"{$question}\"");
        $this->newLine();

        try {
            $bar = $this->output->createProgressBar();
            $bar->start();

            $result = $aiManager->generateAnswer($question);

            $bar->finish();
            $this->newLine(2);

            $this->info('✅ Answer generated successfully!');
            $this->newLine();

            $this->line('Provider: ' . ($aiManager->getPrimaryProvider()?->getProviderName() ?? 'Unknown'));
            $this->line('Model: ' . $result['model']);
            $this->line('Tokens used: ' . $result['tokens_used']);
            $this->newLine();

            $this->line('Answer:');
            $this->line('─────────────────────────────────────────────────');
            $this->line($result['answer']);
            $this->line('─────────────────────────────────────────────────');

            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
