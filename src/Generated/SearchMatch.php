<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.SearchMatch

namespace Ecollectors\CloudSdk\Generated;

final readonly class SearchMatch
{
    public function __construct(
        public string $status,
        public ?int $rank,
        public ?float $score,
        public array $ranking,
        public array $matched_fields,
        public array $synonyms_applied,
        public int $scanned,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: $data['status'],
            rank: $data['rank'],
            score: $data['score'],
            ranking: $data['ranking'],
            matched_fields: $data['matched_fields'],
            synonyms_applied: $data['synonyms_applied'],
            scanned: $data['scanned'],
        );
    }
}
