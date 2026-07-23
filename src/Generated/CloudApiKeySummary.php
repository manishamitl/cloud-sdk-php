<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudApiKeySummary

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudApiKeySummary
{
    public function __construct(
        public string $id,
        public ?string $label,
        public string $prefix,
        public string $last_four,
        public string $status,
        public ?string $expires_at,
        public string $environment,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            label: $data['label'],
            prefix: $data['prefix'],
            last_four: $data['last_four'],
            status: $data['status'],
            expires_at: $data['expires_at'] ?? null,
            environment: $data['environment'],
        );
    }
}
