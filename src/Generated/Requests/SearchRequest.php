<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: search

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class SearchRequest extends Request
{
    public const OPERATION_ID = 'search';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly mixed $q = null,
        public readonly mixed $type = null,
        public readonly mixed $filter = null,
        public readonly mixed $cursor = null,
        public readonly mixed $limit = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/search";
    }


    protected function defaultQuery(): array
    {
        return array_filter([
                'q' => $this->q,
                'type' => $this->type,
                'filter' => $this->filter,
                'cursor' => $this->cursor,
                'limit' => $this->limit,
        ], fn (mixed $value): bool => $value !== null);
    }
}
