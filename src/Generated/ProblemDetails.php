<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.ProblemDetails

namespace Ecollectors\CloudSdk\Generated;

final readonly class ProblemDetails
{
    public function __construct(
        public string $type,
        public string $title,
        public int $status,
        public ?string $detail,
        public ?string $instance,
        public string $code,
        public ?string $docs_url,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'],
            title: $data['title'],
            status: $data['status'],
            detail: $data['detail'] ?? null,
            instance: $data['instance'] ?? null,
            code: $data['code'],
            docs_url: $data['docs_url'] ?? null,
        );
    }
}
