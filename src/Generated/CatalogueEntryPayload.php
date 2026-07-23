<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CatalogueEntryPayload

namespace Ecollectors\CloudSdk\Generated;

final readonly class CatalogueEntryPayload
{
    public function __construct(
        public string $entry_number,
        public string $type,
        public string $name,
        public ?array $catalogue,
        public array $values,
        public array $variants,
        public int $version,
        public string $generated_at,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            entry_number: $data['entry_number'],
            type: $data['type'],
            name: $data['name'],
            catalogue: $data['catalogue'] ?? null,
            values: $data['values'],
            variants: $data['variants'],
            version: $data['version'],
            generated_at: $data['generated_at'],
        );
    }
}
