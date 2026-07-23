<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudAuditEventPage

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudAuditEventPage
{
    public function __construct(
        public array $data,
        public ?string $next_cursor,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            data: $data['data'],
            next_cursor: $data['next_cursor'],
        );
    }
}
