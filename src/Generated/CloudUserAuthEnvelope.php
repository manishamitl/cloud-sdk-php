<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, components.schemas.CloudUserAuthEnvelope

namespace Ecollectors\CloudSdk\Generated;

final readonly class CloudUserAuthEnvelope
{
    public function __construct(
        public array $user,
        public string $token,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            user: $data['user'],
            token: $data['token'],
        );
    }
}
