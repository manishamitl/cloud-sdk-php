<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: rotateApiKey

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;

final class RotateApiKeyRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const OPERATION_ID = 'rotateApiKey';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
        public readonly string $application,
        public readonly string $environment,
        public readonly string $key,
        public readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/applications/{$this->application}/environments/{$this->environment}/keys/{$this->key}/rotate";
    }


    protected function defaultBody(): array
    {
        return $this->data;
    }
}
