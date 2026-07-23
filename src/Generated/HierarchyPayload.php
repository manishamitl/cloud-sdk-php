<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.HierarchyPayload

namespace Ecollectors\CloudSdk\Generated;

final readonly class HierarchyPayload
{
    public function __construct(
        public string $type,
        public ?array $node,
        public string $children,
        public string $entries,
        public string $generated_at,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            node: $data['node'] ?? null,
            children: $data['children'],
            entries: $data['entries'],
            generated_at: $data['generated_at'],
        );
    }
}
