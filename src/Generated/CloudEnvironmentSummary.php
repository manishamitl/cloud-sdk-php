<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudEnvironmentSummary

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudEnvironmentSummary
{
    public function __construct(
        public string $id,
        public string $type,
        public string $status,
        public string $application,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            type: $data['type'],
            status: $data['status'],
            application: $data['application'],
        );
    }
}
