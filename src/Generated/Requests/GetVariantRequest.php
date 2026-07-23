<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: getVariant

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class GetVariantRequest extends Request
{
    public const OPERATION_ID = 'getVariant';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $business_identifier,
        public readonly mixed $include = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/variants/{$this->business_identifier}";
    }


    protected function defaultQuery(): array
    {
        return array_filter([
                'include' => $this->include,
        ], fn (mixed $value): bool => $value !== null);
    }
}
