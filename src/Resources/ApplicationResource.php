<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudApplicationSummary;
use Ecollectors\CloudSdk\Generated\Requests\ArchiveApplicationRequest;
use Ecollectors\CloudSdk\Generated\Requests\CreateApplicationRequest;
use Ecollectors\CloudSdk\Generated\Requests\RestoreApplicationRequest;
use Ecollectors\CloudSdk\Generated\Requests\UpdateApplicationRequest;

final class ApplicationResource extends Resource
{
    /**
     * @param  array{name: string, description?: ?string}  $data
     *
     * @example $client->applications()->create($organisation->id, $product->id, ['name' => 'Website']);
     */
    public function create(string $organisation, string $product, array $data): CloudApplicationSummary
    {
        return CloudApplicationSummary::fromArray($this->send(new CreateApplicationRequest($organisation, $product, $data))->json());
    }

    /**
     * @param  array{name?: string, description?: ?string}  $data
     *
     * @example $client->applications()->update($organisation->id, $product->id, $application->id, ['description' => 'Storefront integration']);
     */
    public function update(string $organisation, string $product, string $application, array $data): CloudApplicationSummary
    {
        return CloudApplicationSummary::fromArray($this->send(new UpdateApplicationRequest($organisation, $product, $application, $data))->json());
    }

    /**
     * @example $client->applications()->archive($organisation->id, $product->id, $application->id);
     */
    public function archive(string $organisation, string $product, string $application): CloudApplicationSummary
    {
        return CloudApplicationSummary::fromArray($this->send(new ArchiveApplicationRequest($organisation, $product, $application))->json());
    }

    /**
     * @example $client->applications()->restore($organisation->id, $product->id, $application->id);
     */
    public function restore(string $organisation, string $product, string $application): CloudApplicationSummary
    {
        return CloudApplicationSummary::fromArray($this->send(new RestoreApplicationRequest($organisation, $product, $application))->json());
    }
}
