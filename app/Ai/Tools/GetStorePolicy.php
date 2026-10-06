<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Validation\Rule;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class GetStorePolicy implements Tool
{
    public function description(): Stringable|string
    {
        return 'Consulta las políticas oficiales de la tienda sobre devoluciones, envíos o garantía.';
    }

    public function handle(Request $request): Stringable|string
    {
        $validated = $request->validate([
            'topic' => [
                'required',
                'string',
                Rule::in([
                    'returns',
                    'shipping',
                    'warranty',
                ]),
            ],
        ]);

        return (string) config(
            "store.policies.{$validated['topic']}"
        );
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'topic' => $schema
                ->string()
                ->enum([
                    'returns',
                    'shipping',
                    'warranty',
                ])
                ->description(
                    'Política que se desea consultar.'
                )
                ->required(),
        ];
    }
}