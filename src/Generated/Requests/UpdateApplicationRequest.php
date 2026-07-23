<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: updateApplication

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;

final class UpdateApplicationRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const OPERATION_ID = 'updateApplication';

    protected Method $method = Method::PATCH;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
        public readonly string $application,
        public readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/applications/{$this->application}";
    }


    protected function defaultBody(): array
    {
        return $this->data;
    }
}
