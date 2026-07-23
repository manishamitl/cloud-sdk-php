<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudProductSummary

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudProductSummary
{
    public function __construct(
        public string $id,
        public string $name,
        public string $slug,
        public string $status,
        public string $organisation,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            slug: $data['slug'],
            status: $data['status'],
            organisation: $data['organisation'],
        );
    }
}
