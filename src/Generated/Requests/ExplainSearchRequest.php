<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: explainSearch

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class ExplainSearchRequest extends Request
{
    public const OPERATION_ID = 'explainSearch';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly mixed $q = null,
        public readonly mixed $type = null,
        public readonly mixed $identity = null,
        public readonly mixed $filter = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/search/explain";
    }


    protected function defaultQuery(): array
    {
        return array_filter([
                'q' => $this->q,
                'type' => $this->type,
                'identity' => $this->identity,
                'filter' => $this->filter,
        ], fn (mixed $value): bool => $value !== null);
    }
}
