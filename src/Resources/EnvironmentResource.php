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
    /**
     * @example $client->environments()->enable($organisation->id, $product->id, $application->id, $environment->id);
     */
    public function enable(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new EnableEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    /**
     * @example $client->environments()->disable($organisation->id, $product->id, $application->id, $environment->id);
     */
    public function disable(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new DisableEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    /**
     * @example $client->environments()->archive($organisation->id, $product->id, $application->id, $environment->id);
     */
    public function archive(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new ArchiveEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }

    /**
     * @example $client->environments()->restore($organisation->id, $product->id, $application->id, $environment->id);
     */
    public function restore(string $organisation, string $product, string $application, string $environment): CloudEnvironmentSummary
    {
        return CloudEnvironmentSummary::fromArray($this->send(new RestoreEnvironmentRequest($organisation, $product, $application, $environment))->json());
    }
}
