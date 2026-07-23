<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudApplicationSummary

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudApplicationSummary
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description,
        public string $status,
        public string $product,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            description: $data['description'] ?? null,
            status: $data['status'],
            product: $data['product'],
        );
    }
}
