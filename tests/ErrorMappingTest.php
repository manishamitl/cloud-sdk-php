<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\EcollectorsCloud;
use Ecollectors\CloudSdk\Exceptions\ConflictException;
use Ecollectors\CloudSdk\Exceptions\NotFoundException;
use Ecollectors\CloudSdk\Exceptions\RateLimitedException;
use Ecollectors\CloudSdk\Exceptions\UnauthenticatedException;
use Ecollectors\CloudSdk\Generated\Requests\ArchiveProductRequest;
use Ecollectors\CloudSdk\Generated\Requests\GetVariantRequest;
use Ecollectors\CloudSdk\Generated\Requests\SearchRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

afterEach(fn () => MockClient::destroyGlobal());

it('maps a 404 problem+json response to NotFoundException', function () {
    MockClient::global([
        GetVariantRequest::class => MockResponse::make(problemDetails('not_found', 404), 404),
    ]);

    EcollectorsCloud::withApiKey('fake-key')->knowledge()->variant('NO-SUCH-VARIANT');
})->throws(NotFoundException::class);

it('maps a 401 problem+json response to UnauthenticatedException', function () {
    MockClient::global([
        SearchRequest::class => MockResponse::make(problemDetails('unauthenticated', 401), 401),
    ]);

    EcollectorsCloud::withApiKey('bad-key')->search()->query('kgv half rupee');
})->throws(UnauthenticatedException::class);

it('maps a 429 problem+json response to RateLimitedException', function () {
    MockClient::global([
        SearchRequest::class => MockResponse::make(problemDetails('rate_limited', 429), 429),
    ]);

    EcollectorsCloud::withApiKey('fake-key')->search()->query('kgv half rupee');
})->throws(RateLimitedException::class);

it('maps a 409 problem+json response to ConflictException, and preserves the fields', function () {
    MockClient::global([
        ArchiveProductRequest::class => MockResponse::make([
            ...problemDetails('conflict', 409),
            'detail' => 'Cannot archive product "NumisPoint": 1 active application still belongs to it.',
        ], 409),
    ]);

    try {
        EcollectorsCloud::withUserToken('fake-token')->products()->archive('org_1', 'prod_1');
        expect(false)->toBeTrue('Expected ConflictException to be thrown.');
    } catch (ConflictException $e) {
        expect($e->status)->toBe(409)
            ->and($e->problemCode)->toBe('conflict')
            ->and($e->detail)->toContain('active application');
    }
});

/**
 * @return array<string, mixed>
 */
function problemDetails(string $code, int $status): array
{
    return [
        'type' => 'about:blank',
        'title' => ucfirst(str_replace('_', ' ', $code)),
        'status' => $status,
        'code' => $code,
        'docs_url' => "https://docs.example.test/errors/{$code}",
    ];
}
