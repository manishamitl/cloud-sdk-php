<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\EcollectorsCloud;
use Ecollectors\CloudSdk\Generated\Requests\GetVariantRequest;
use Ecollectors\CloudSdk\Generated\Requests\SearchRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

afterEach(fn () => MockClient::destroyGlobal());

it('sends an ApiKeyAuth token as a Bearer header', function () {
    $mock = MockClient::global([
        SearchRequest::class => MockResponse::make([
            'meta' => [], 'query' => [], 'filters' => [], 'facets' => [], 'results' => [], 'links' => [],
        ], 200),
    ]);

    EcollectorsCloud::withApiKey('ec_production_realkey123')->search()->query('kgv half rupee');

    $header = $mock->getLastPendingRequest()->headers()->get('Authorization');

    expect($header)->toBe('Bearer ec_production_realkey123');
});

it('sends a CloudUserAuth token as a Bearer header — the identical transport as ApiKeyAuth', function () {
    $mock = MockClient::global([
        GetVariantRequest::class => MockResponse::make([
            'identifier' => 'KGV1R-1911-CAL', 'requested_identifier' => 'KGV1R-1911-CAL',
            'type' => 'variant', 'name' => 'KGV Half Rupee', 'values' => [], 'enrichment' => [],
            'assets' => [], 'version' => 1, 'generated_at' => '2026-07-23T00:00:00Z',
        ], 200),
    ]);

    EcollectorsCloud::withUserToken('sanctum-token-abc')->knowledge()->variant('KGV1R-1911-CAL');

    $header = $mock->getLastPendingRequest()->headers()->get('Authorization');

    expect($header)->toBe('Bearer sanctum-token-abc');
});
