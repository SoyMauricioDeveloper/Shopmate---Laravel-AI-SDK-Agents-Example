<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchProducts implements Tool
{
    public function description(): Stringable|string
    {
        return <<<'DESCRIPTION'
        Busca productos activos en el catálogo de la tienda.

        Debe utilizarse cuando el usuario pregunte por:
        - productos
        - precios
        - disponibilidad
        - stock
        - recomendaciones
        - presupuestos

        El parámetro query debe contener únicamente el tipo de producto
        o palabra principal de búsqueda.

        Ejemplos:
        "mouse"
        "teclado"
        "monitor"
        "hub"

        No incluir frases como "para trabajar", "necesito", "quiero",
        ni el presupuesto dentro de query.
        DESCRIPTION;
    }

    public function handle(Request $request): Stringable|string
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:100'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'max:100000'],
        ]);

        $query = trim($validated['query']);
        $maxPrice = $validated['max_price'] ?? null;

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when(
                $maxPrice !== null,
                fn ($builder) => $builder->where('price', '<=', $maxPrice)
            )
            ->orderBy('price')
            ->limit(5)
            ->get([
                'name',
                'category',
                'description',
                'price',
                'stock',
            ]);

        if ($products->isEmpty()) {
            return json_encode([
                'found' => false,
                'query' => $query,
                'max_price' => $maxPrice,
                'message' => 'No se encontraron productos que coincidan con la búsqueda.',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        return json_encode([
            'found' => true,
            'query' => $query,
            'max_price' => $maxPrice,
            'products' => $products->map(fn ($product) => [
                'name' => $product->name,
                'category' => $product->category,
                'description' => $product->description,
                'price' => (float) $product->price,
                'stock' => (int) $product->stock,
            ])->values(),
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema
                ->string()
                ->description(
                    'Únicamente el tipo de producto o palabra principal que debe buscarse. '
                    .'Ejemplos: mouse, teclado, monitor, hub. '
                    .'No incluir presupuesto ni frases adicionales como "para trabajar".'
                )
                ->required(),

            'max_price' => $schema
                ->number()
                ->min(0)
                ->description(
                    'Presupuesto máximo exacto indicado por el usuario. '
                    .'Por ejemplo, si dice "máximo 60 dólares", enviar 60.'
                ),
        ];
    }
}