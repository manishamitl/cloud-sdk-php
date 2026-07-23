# eCollectors Cloud — PHP/Laravel SDK

The official PHP client for the [eCollectors Cloud API](https://docs.ecollectors.cloud)
(M6.2, `ecollectors/cloud-sdk`). Generated models and endpoint definitions,
wrapped by a small hand-maintained facade — the only class you ever import
is `EcollectorsCloud`.

**Full API reference, authentication guide, and error code reference**:
[docs.ecollectors.cloud](https://docs.ecollectors.cloud) — this README stays
short on purpose; the docs site is the canonical source, this SDK just
wraps it.

## Install

```bash
composer require ecollectors/cloud-sdk
```

## Authentication

The platform has two independent auth modes, and this SDK has one
constructor for each:

```php
use Ecollectors\CloudSdk\EcollectorsCloud;

// An `ec_{env}_…` API key — the data plane (Search, Knowledge Delivery).
$client = EcollectorsCloud::withApiKey('ec_production_...');

// A Sanctum token from register()/login() — self-service account
// management (Organisations, Products, Applications, Environments, Keys).
$client = EcollectorsCloud::withUserToken($token);
```

## Examples

**Search** (data plane, API key):

```php
$results = $client->search()->query('kgv half rupee 1912');
```

**Knowledge Delivery** (data plane, API key):

```php
$variant = $client->knowledge()->variant('KGV1R-1911-CAL');
```

**Self-service account management** (CloudUser token):

```php
$auth = EcollectorsCloud::withApiKey('')->auth()->register([
    'name' => 'Ada Lovelace',
    'email' => 'ada@example.test',
    'password' => 'correct-horse-battery-staple',
]);

$client = EcollectorsCloud::withUserToken($auth->token);

$organisation = $client->organisations()->create(['name' => 'NumisPoint Ltd']);
$product = $client->products()->create($organisation->id, ['name' => 'NumisPoint']);
$application = $client->applications()->create($organisation->id, $product->id, ['name' => 'Website']);
```

## Errors

Every failure throws a typed exception under `Ecollectors\CloudSdk\Exceptions`,
one per API error code — `NotFoundException`, `ValidationException`,
`UnauthenticatedException`, `RateLimitedException`, `ConflictException`, and
so on — all extending `EcollectorsCloudException`, which carries the
original `status`, `problemCode`, `detail`, and `docsUrl`.

```php
use Ecollectors\CloudSdk\Exceptions\NotFoundException;

try {
    $client->knowledge()->variant('NO-SUCH-VARIANT');
} catch (NotFoundException $e) {
    // $e->status === 404, $e->problemCode === 'not_found'
}
```

## Contract version

Every release is generated from a specific, pinned commit of the platform's
OpenAPI contract:

```php
EcollectorsCloud::contractVersion(); // '452ffd6ab234...'
```

## Development

This package's `src/Generated/` tree is generated, never hand-edited — see
`generator/` for the (deliberately narrow) generation logic and
`bin/sync-contract` / `bin/generate` for the regeneration workflow.

```bash
composer install
composer generate   # regenerate src/Generated/ from contract/openapi.yaml
composer test
```
