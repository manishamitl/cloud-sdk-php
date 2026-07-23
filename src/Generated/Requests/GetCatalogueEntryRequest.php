<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: getCatalogueEntry

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class GetCatalogueEntryRequest extends Request
{
    public const OPERATION_ID = 'getCatalogueEntry';

    protected Method $method = Method::GET;

    public function __construct(
        public readonly string $entry_number,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/catalogue-entries/{$this->entry_number}";
    }
}
