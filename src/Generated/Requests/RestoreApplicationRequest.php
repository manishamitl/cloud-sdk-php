<?php

declare(strict_types=1);

// GENERATED — do not edit by hand. Run `composer generate` to rebuild.
// Source: contract/openapi.yaml, operationId: restoreApplication

namespace Ecollectors\CloudSdk\Generated\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class RestoreApplicationRequest extends Request
{
    public const OPERATION_ID = 'restoreApplication';

    protected Method $method = Method::POST;

    public function __construct(
        public readonly string $organisation,
        public readonly string $product,
        public readonly string $application,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/organisations/{$this->organisation}/products/{$this->product}/applications/{$this->application}/restore";
    }
}
