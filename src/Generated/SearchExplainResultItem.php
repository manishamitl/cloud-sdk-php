<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.SearchExplainResultItem

namespace Ecollectors\CloudSdk\Generated;

final readonly class SearchExplainResultItem
{
    public function __construct(
        public string $type,
        public string $identity,
        public string $title,
        public ?string $subtitle,
        public ?string $snippet,
        public array $hierarchy,
        public string $url,
        public array $match,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            identity: $data['identity'],
            title: $data['title'],
            subtitle: $data['subtitle'],
            snippet: $data['snippet'],
            hierarchy: $data['hierarchy'],
            url: $data['url'],
            match: $data['match'],
        );
    }
}
