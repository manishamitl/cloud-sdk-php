# eCollectors Cloud — PHP/Laravel SDK

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

The official PHP client for the [eCollectors Cloud API](https://docs.ecollectors.in)
(M6.2, `ecollectors/cloud-sdk`). Generated models and endpoint definitions,
wrapped by a small hand-maintained facade — the only class you ever import
is `EcollectorsCloud`.

**Full API reference, authentication guide, and error code reference**:
[docs.ecollectors.in](https://docs.ecollectors.in) — this README stays
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

## Versioning

This package follows [Semantic Versioning](https://semver.org/). What that
means concretely for a generated-plus-hand-wrapped SDK:

- **Patch** — internal fixes (generator bugs, dependency bumps) with no
  change to any public method's signature or behaviour.
- **Minor** — a new operation the contract added (a new `Resource` method
  or resource), or a new optional parameter on an existing method. The
  contract's own additive-only guarantee (C-4: fields are added, never
  removed or renamed without a new `/v2` server) means a new contract
  revision never breaks an existing SDK release on its own — the SDK only
  needs a new *minor* to expose what's new.
- **Major** — a public method's signature or behaviour changes in a way
  existing calling code would need to change for. This SDK targets exactly
  one contract server (`/v1`); a hypothetical future `/v2` would be a new
  major release, not an in-place change to this one.

**Contract compatibility**: `EcollectorsCloud::contractVersion()` (above)
names the exact commit an SDK release was generated from. Any `1.x`
release of this SDK is safe against any contract revision at or after that
commit, for the reason above — additive contract changes never require an
SDK upgrade to keep working, only to use what's new.

**Deprecation policy**: if an operation is ever removed from the contract
(a breaking, major-only event under C-4), the corresponding `Resource`
method is marked `@deprecated` in its docblock for one full minor release
cycle before removal, rather than disappearing in the same release that
drops the operation.

## Development

This package's `src/Generated/` tree is generated, never hand-edited — see
`generator/` for the (deliberately narrow) generation logic and
`bin/sync-contract` / `bin/generate` for the regeneration workflow.
`docs/snippets.json` (the per-operation PHP code sample the platform's
docs site consumes) is also generated, from each `Resource` method's
`@example` PHPDoc tag — see `generator/SnippetExtractor.php`.

```bash
composer install
composer generate            # regenerate src/Generated/ from contract/openapi.yaml
composer generate-snippets   # regenerate docs/snippets.json from @example tags
composer test
```

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

MIT — see [LICENSE](LICENSE).
