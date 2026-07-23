<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.VariantPayload

namespace Ecollectors\CloudSdk\Generated;

final readonly class VariantPayload
{
    public function __construct(
        public string $identifier,
        public string $requested_identifier,
        public string $type,
        public string $name,
        public ?array $display,
        public ?array $catalogue,
        public array $values,
        public array $enrichment,
        public array $assets,
        public int $version,
        public string $generated_at,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            identifier: $data['identifier'],
            requested_identifier: $data['requested_identifier'],
            type: $data['type'],
            name: $data['name'],
            display: $data['display'] ?? null,
            catalogue: $data['catalogue'] ?? null,
            values: $data['values'],
            enrichment: $data['enrichment'],
            assets: $data['assets'],
            version: $data['version'],
            generated_at: $data['generated_at'],
        );
    }
}
