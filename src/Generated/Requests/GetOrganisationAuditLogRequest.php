<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: getOrganisationAuditLog

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class GetOrganisationAuditLogRequest extends Request
{
    public const OPERATION_ID = 'getOrganisationAuditLog';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $organisation,
        public readonly mixed $cursor = null,
        public readonly mixed $limit = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/audit-log";
    }


    protected function defaultQuery(): array
    {
        return array_filter([
                'cursor' => $this->cursor,
                'limit' => $this->limit,
        ], fn (mixed $value): bool => $value !== null);
    }
}
