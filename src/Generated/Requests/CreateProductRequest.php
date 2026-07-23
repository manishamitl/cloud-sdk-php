<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: createProduct

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;

final class CreateProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    public const OPERATION_ID = 'createProduct';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products";
    }


    protected function defaultBody(): array
    {
        return $this->data;
    }
}
