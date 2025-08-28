<?php

namespace App\Jobs;

use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateIdeaFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $idea;

    /**
     * Create a new job instance.
     */
    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
             // Falls kein Key gesetzt, Dummy-Feedback
        $apiKey = config('services.openai.key');

        if (!$apiKey) {
            $this->idea->update([
                'feedback' => '⚠️ Kein API-Key konfiguriert. Demo-Feedback: Tolle Idee!'
            ]);
            return;
        }

        $client = OpenAI::client($apiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Du bist ein hilfreicher Produktmanager.'],
                ['role' => 'user', 'content' => "Gib kurzes Feedback zu dieser Idee: {$this->idea->title} - {$this->idea->description}"]
            ],
        ]);

        $feedback = $response->choices[0]->message->content ?? 'Kein Feedback generiert';

        $this->idea->update(['feedback' => $feedback]);
    }
}