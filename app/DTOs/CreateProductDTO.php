<?php

namespace App\DTOs;

use App\Http\Requests\StoreProductRequest;

class CreateProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $details,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            name: $request->name,
            details: $request->details,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'details' => $this->details,
        ];
    }
}
