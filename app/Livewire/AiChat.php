<?php

namespace App\Livewire;

use App\Ai\Agents\StoreAssistant;
use App\Services\ChatVisitorResolver;
use BackedEnum;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Ai\Contracts\ConversationStore;
use Livewire\Component;
use Throwable;

class AiChat extends Component
{
    public string $message = '';

    public array $messages = [];

    public ?string $error = null;

    public function mount(): void
    {
        $this->loadMessages();
    }

    public function send(): void
    {
        $this->reset('error');

        $validated = $this->validate([
            'message' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);

        $prompt = trim($validated['message']);

        $visitor = app(ChatVisitorResolver::class)
            ->resolve();

        $rateLimitKey = sprintf(
            'ai-chat:%s:%s',
            $visitor->getKey(),
            request()->ip()
        );

        if (
            RateLimiter::tooManyAttempts(
                $rateLimitKey,
                10
            )
        ) {
            $this->error = 'Has enviado demasiados mensajes. Espera un momento e inténtalo de nuevo.';

            return;
        }

        RateLimiter::hit(
            $rateLimitKey,
            60
        );

        $conversationId = session(
            'ai.conversation_id'
        );

        $store = app(
            ConversationStore::class
        );

        if (
            $conversationId &&
            ! $store->conversationBelongsTo(
                $conversationId,
                $visitor->getMorphClass(),
                $visitor->getKey()
            )
        ) {
            session()->forget(
                'ai.conversation_id'
            );

            $conversationId = null;
        }

        try {
            $response = (new StoreAssistant())
                ->continueOrStart(
                    $conversationId,
                    as: $visitor
                )
                ->prompt($prompt);

            session([
                'ai.conversation_id'
                    => $response->conversationId,
            ]);

            $this->message = '';

            $this->loadMessages();
        } catch (Throwable $exception) {
            report($exception);

            $this->error = config('app.debug')
                ? $exception->getMessage()
                : 'No pudimos responder en este momento.';
        }
    }

    public function newConversation(): void
    {
        session()->forget(
            'ai.conversation_id'
        );

        $this->message = '';
        $this->messages = [];
        $this->error = null;
    }

    private function loadMessages(): void
    {
        $this->messages = [];

        $conversationId = session(
            'ai.conversation_id'
        );

        if (! $conversationId) {
            return;
        }

        $visitor = app(ChatVisitorResolver::class)
            ->resolve();

        $store = app(
            ConversationStore::class
        );

        if (
            ! $store->conversationBelongsTo(
                $conversationId,
                $visitor->getMorphClass(),
                $visitor->getKey()
            )
        ) {
            session()->forget(
                'ai.conversation_id'
            );

            return;
        }

        $paginator = $store
            ->paginateConversationMessages(
                $conversationId,
                perPage: 50
            );

        $this->messages = collect(
            $paginator->items()
        )
            ->reverse()
            ->map(function ($storedMessage) {
                $role = $storedMessage->role;

                if ($role instanceof BackedEnum) {
                    $role = $role->value;
                }

                return [
                    'role' => (string) $role,
                    'content' => (string) $storedMessage->content,
                ];
            })
            ->filter(
                fn (array $message) =>
                    in_array(
                        $message['role'],
                        ['user', 'assistant'],
                        true
                    ) &&
                    $message['content'] !== ''
            )
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.ai-chat');
    }
}