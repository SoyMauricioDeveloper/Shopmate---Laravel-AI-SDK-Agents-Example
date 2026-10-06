<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchProducts;
use App\Ai\Tools\GetStorePolicy;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\MaxTokens;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::OpenAI)]
#[Model('gpt-6-luna')]
#[MaxSteps(6)]
#[MaxTokens(800)]
#[Timeout(60)]
class StoreAssistant implements Agent, Conversational, HasTools
{
    use Promptable;
    use RemembersConversations;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        Eres ShopMate, un asistente de una tienda de tecnología.

        Reglas:
        - Responde siempre en español.
        - Sé claro, breve y amable.
        - Cuando el usuario pregunte por productos, precios, stock,
          recomendaciones o presupuestos, DEBES utilizar SearchProducts.
        - Nunca inventes productos, precios ni disponibilidad.
        - Utiliza únicamente los productos devueltos por SearchProducts.
        - Respeta exactamente el presupuesto indicado por el usuario.
        - No cambies ni redondees el presupuesto.
        - Para SearchProducts, envía en query únicamente el tipo principal
          de producto.

        Ejemplos:
        Usuario: "Necesito un mouse para trabajar por máximo 60 dólares."
        SearchProducts:
        query = "mouse"
        max_price = 60

        Usuario: "Busco un teclado de menos de 100 dólares."
        SearchProducts:
        query = "teclado"
        max_price = 100

        - Si la herramienta no encuentra resultados, dilo claramente.
        - Si encuentra productos, menciona nombre, precio y disponibilidad.
        - No uses Markdown complejo.
        PROMPT;
    }

    public function tools(): iterable
    {
        return [
            new SearchProducts(),
            new GetStorePolicy(),
        ];
    }
}