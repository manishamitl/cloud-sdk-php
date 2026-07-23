<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: revokeApiKey

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class RevokeApiKeyRequest extends Request
{
    public const OPERATION_ID = 'revokeApiKey';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
        public readonly string $application,
        public readonly string $environment,
        public readonly string $key,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/applications/{$this->application}/environments/{$this->environment}/keys/{$this->key}/revoke";
    }
}
