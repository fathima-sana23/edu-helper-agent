<?php

namespace App\Agents;

use App\Models\ChatMessage;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class EduHelperAgent
{
    private array $allowedTopics = [
        'solar system',
        'fractions',
        'water cycle'
    ];

    public function respond(string $message, string $sessionId): string
    {
        if (! $this->isAllowedTopic($message)) {
            return "I can only help with Solar System, Fractions, or Water Cycle for now 😊";
        }

        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'user',
            'message' => $message
        ]);

        $history = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id')
            ->get()
            ->map(fn ($row) => [
                'role' => $row->role,
                'content' => $row->message
            ])
            ->toArray();

        $systemPrompt = <<<PROMPT
              You are EduHelperAgent. Greet the student politely.Explain concepts in simple language for school students.ONLY answer questions about:
          - Solar System
          - Fractions
          - Water Cycle
         Limit your reply to a maximum of 60 words.
        PROMPT;

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => array_merge(
                    [['role' => 'system', 'content' => $systemPrompt]],
                    $history
                ),
                'max_tokens' => 120,
            ]);

            $reply = trim($response->choices[0]->message->content);

        } catch (Exception $e) {
            $reply = "Hello! I’m currently running in demo mode. Please configure a valid LLM API key to get live answers 😊";
        }

        ChatMessage::create([
            'session_id' => $sessionId,
            'role' => 'assistant',
            'message' => $reply
        ]);

        return $reply;
    }

    private function isAllowedTopic(string $message): bool
    {
        $message = strtolower($message);

        foreach ($this->allowedTopics as $topic) {
            if (str_contains($message, $topic)) {
                return true;
            }
        }

        return false;
    }
}
