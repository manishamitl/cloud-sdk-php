<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: issueApiKey

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;

final class IssueApiKeyRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const OPERATION_ID = 'issueApiKey';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
        public readonly string $application,
        public readonly string $environment,
        public readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/applications/{$this->application}/environments/{$this->environment}/keys";
    }


    protected function defaultBody(): array
    {
        return $this->data;
    }
}
