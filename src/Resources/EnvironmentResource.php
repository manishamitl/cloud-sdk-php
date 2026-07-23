<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudEnvironmentSummary;
use Ecollectors\CloudSdk\Generated\Requests\ArchiveEnvironmentRequest;
use Ecollectors\CloudSdk\Generated\Requests\DisableEnvironmentRequest;
use Ecollectors\CloudSdk\Generated\Requests\EnableEnvironmentRequest;
use Ecollectors\CloudSdk\Generated\Requests\RestoreEnvironmentRequest;

/**
 * No `create()` — every Environment is born only as `ApplicationResource::create()`'s
 * guaranteed Production side effect, exactly as the platform's own M4.3
 * domain rule works. This resource only ever moves an existing Environment
 * through its lifecycle.
 */
final class EnvironmentResource extends Resource
{
    public function enable(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new EnableEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    public function disable(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new DisableEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    public function archive(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new ArchiveEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    public function restore(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new RestoreEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }
}
