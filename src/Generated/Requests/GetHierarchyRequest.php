<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: getHierarchy

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class GetHierarchyRequest extends Request
{
    public const OPERATION_ID = 'getHierarchy';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $node_slug_path,
        public readonly mixed $cursor = null,
        public readonly mixed $limit = null,
        public readonly mixed $entries_cursor = null,
        public readonly mixed $entries_limit = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/hierarchy/{$this->node_slug_path}";
    }


    protected function defaultQuery(): array
    {
        return array_filter([
                'cursor' => $this->cursor,
                'limit' => $this->limit,
                'entries_cursor' => $this->entries_cursor,
                'entries_limit' => $this->entries_limit,
        ], fn (mixed $value): bool => $value !== null);
    }
}
