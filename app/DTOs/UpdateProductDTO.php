<?php

namespace App\DTOs;

use App\Http\Requests\UpdateProductRequest;

class UpdateProductDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public readonly string $details,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            details: $request->details,
        );
    }

    public function toArray(): array
    {
        return [
            'details' => $this->details,
        ];
    }
}
