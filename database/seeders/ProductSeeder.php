<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        Product::create([
            'name' => 'Logitech M650',
            'slug' => 'logitech-m650',
            'category' => 'mouse',
            'description' => 'Mouse inalámbrico silencioso para productividad y oficina.',
            'price' => 49.99,
            'stock' => 15,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Logitech MX Master 3S',
            'slug' => 'logitech-mx-master-3s',
            'category' => 'mouse',
            'description' => 'Mouse inalámbrico premium para productividad profesional.',
            'price' => 99.99,
            'stock' => 8,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Keychron K2',
            'slug' => 'keychron-k2',
            'category' => 'teclado',
            'description' => 'Teclado mecánico inalámbrico compacto.',
            'price' => 89.90,
            'stock' => 12,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Teclado Mecánico Pro X',
            'slug' => 'teclado-mecanico-pro-x',
            'category' => 'teclado',
            'description' => 'Teclado mecánico de tamaño completo para programación y gaming.',
            'price' => 129.00,
            'stock' => 5,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Hub USB-C 8 en 1',
            'slug' => 'hub-usb-c-8-en-1',
            'category' => 'accesorio',
            'description' => 'Hub USB-C con HDMI, USB, lector de tarjetas y Power Delivery.',
            'price' => 39.99,
            'stock' => 20,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Monitor LG 27 IPS',
            'slug' => 'monitor-lg-27-ips',
            'category' => 'monitor',
            'description' => 'Monitor IPS de 27 pulgadas pensado para productividad.',
            'price' => 299.99,
            'stock' => 6,
            'is_active' => true,
        ]);
    }
}