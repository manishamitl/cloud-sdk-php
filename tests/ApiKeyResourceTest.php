<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\EcollectorsCloud;
use Ecollectors\CloudSdk\Generated\CloudApiKeyIssuedSummary;
use Ecollectors\CloudSdk\Generated\CloudApiKeySummary;
use Ecollectors\CloudSdk\Generated\Requests\IssueApiKeyRequest;
use Ecollectors\CloudSdk\Generated\Requests\RevokeApiKeyRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

afterEach(fn () => MockClient::destroyGlobal());

/**
 * Mirrors the platform's own frozen §4.2 invariant: the plaintext secret
 * is only ever present on the issue/rotate response shape
 * (CloudApiKeyIssuedSummary), never on the plain CloudApiKeySummary a
 * revoke/update response maps to.
 */
it('returns the plaintext secret exactly once, on issue', function () {
    MockClient::global([
        IssueApiKeyRequest::class => MockResponse::make([
            'id' => 'key_abc', 'label' => 'Website key', 'prefix' => 'ec_production_abc123',
            'last_four' => 'wxyz', 'status' => 'active', 'environment' => 'env_xyz',
            'secret' => 'ec_production_abc123_wxyz_checksum',
        ], 201),
    ]);

    $key = EcollectorsCloud::withUserToken('fake-token')
        ->apiKeys()->issue('org_1', 'prod_1', 'app_1', 'env_1', ['label' => 'Website key']);

    expect($key)->toBeInstanceOf(CloudApiKeyIssuedSummary::class)
        ->and($key->secret)->toBe('ec_production_abc123_wxyz_checksum');
});

it('revoke never carries a secret field on its response DTO type', function () {
    MockClient::global([
        RevokeApiKeyRequest::class => MockResponse::make([
            'id' => 'key_abc', 'label' => 'Website key', 'prefix' => 'ec_production_abc123',
            'last_four' => 'wxyz', 'status' => 'revoked', 'environment' => 'env_xyz',
        ], 200),
    ]);

    $key = EcollectorsCloud::withUserToken('fake-token')
        ->apiKeys()->revoke('org_1', 'prod_1', 'app_1', 'env_1', 'key_abc');

    expect($key)->toBeInstanceOf(CloudApiKeySummary::class)
        ->and($key->status)->toBe('revoked');
    // CloudApiKeySummary has no $secret property at all — asserted by the
    // type itself, not by a runtime check: revoke() is statically incapable
    // of returning a class with a secret field.
});
