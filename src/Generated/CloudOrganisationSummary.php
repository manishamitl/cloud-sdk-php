<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudOrganisationSummary

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudOrganisationSummary
{
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public string $plan,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            status: $data['status'],
            plan: $data['plan'],
        );
    }
}
