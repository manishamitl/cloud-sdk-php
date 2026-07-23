<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.KnowledgeValue

namespace Ecollectors\CloudSdk\Generated;

final readonly class KnowledgeValue
{
    public function __construct(
        public string $label,
        public string $type,
        public string $value,
        public string $raw,
        public ?array $citations,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            type: $data['type'],
            value: $data['value'],
            raw: $data['raw'],
            citations: $data['citations'] ?? null,
        );
    }
}
