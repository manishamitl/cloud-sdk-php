<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: archiveProduct

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class ArchiveProductRequest extends Request
{
    public const OPERATION_ID = 'archiveProduct';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/archive";
    }
}
