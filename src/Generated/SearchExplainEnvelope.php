<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.SearchExplainEnvelope

namespace Ecollectors\CloudSdk\Generated;

final readonly class SearchExplainEnvelope
{
    public function __construct(
        public array $meta,
        public array $query,
        public array $filters,
        public array $facets,
        public array $results,
        public array $links,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            meta: $data['meta'],
            query: $data['query'],
            filters: $data['filters'],
            facets: $data['facets'],
            results: $data['results'],
            links: $data['links'],
        );
    }
}
