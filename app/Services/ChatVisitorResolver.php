<?php

namespace App\Services;

use App\Models\ChatVisitor;
use Illuminate\Support\Str;

class ChatVisitorResolver
{
    public function resolve(): ChatVisitor
    {
        $visitorId = session('ai.visitor_id');

        if ($visitorId) {
            $visitor = ChatVisitor::find($visitorId);

            if ($visitor) {
                return $visitor;
            }
        }

        $visitor = ChatVisitor::create([
            'uuid' => (string) Str::uuid(),
        ]);

        session([
            'ai.visitor_id' => $visitor->getKey(),
        ]);

        return $visitor;
    }
}