<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudOrganisationSummary;
use Ecollectors\CloudSdk\Generated\Requests\CreateOrganisationForOwnerRequest;
use Ecollectors\CloudSdk\Generated\Requests\UpdateOrganisationRequest;

final class OrganisationResource extends Resource
{
    /**
     * @param  array{name: string, contact_name?: ?string, contact_email?: ?string}  $data
     *
     * @example $client->organisations()->create(['name' => 'NumisPoint Ltd']);
     */
    public function create(array $data): CloudOrganisationSummary
    {
        return CloudOrganisationSummary::fromArray($this->send(new CreateOrganisationForOwnerRequest($data))->json());
    }

    /**
     * @param  array{name?: string, contact_name?: ?string, contact_email?: ?string}  $data
     *
     * @example $client->organisations()->update($organisation->id, ['contact_email' => 'billing@numispoint.test']);
     */
    public function update(string $organisation, array $data): CloudOrganisationSummary
    {
        return CloudOrganisationSummary::fromArray($this->send(new UpdateOrganisationRequest($organisation, $data))->json());
    }
}
