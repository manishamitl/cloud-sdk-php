<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\EcollectorsCloud;
use Ecollectors\CloudSdk\Generated\CloudOrganisationSummary;
use Ecollectors\CloudSdk\Generated\Requests\CreateOrganisationForOwnerRequest;
use Ecollectors\CloudSdk\Generated\Requests\UpdateOrganisationRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

afterEach(fn () => MockClient::destroyGlobal());

it('creates an organisation and returns a typed DTO', function () {
    MockClient::global([
        CreateOrganisationForOwnerRequest::class => MockResponse::make([
            'id' => 'org_abc123', 'name' => 'NumisPoint Ltd', 'status' => 'active', 'plan' => 'Starter',
        ], 201),
    ]);

    $organisation = EcollectorsCloud::withUserToken('fake-token')
        ->organisations()->create(['name' => 'NumisPoint Ltd']);

    expect($organisation)->toBeInstanceOf(CloudOrganisationSummary::class)
        ->and($organisation->id)->toBe('org_abc123')
        ->and($organisation->plan)->toBe('Starter');
});

it('updates an organisation and returns the updated DTO', function () {
    MockClient::global([
        UpdateOrganisationRequest::class => MockResponse::make([
            'id' => 'org_abc123', 'name' => 'Renamed Ltd', 'status' => 'active', 'plan' => 'Starter',
        ], 200),
    ]);

    $organisation = EcollectorsCloud::withUserToken('fake-token')
        ->organisations()->update('org_abc123', ['name' => 'Renamed Ltd']);

    expect($organisation->name)->toBe('Renamed Ltd');
});
