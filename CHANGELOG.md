# Changelog

All notable changes to this package are documented here. Format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/); versioning
follows [Semantic Versioning](https://semver.org/) — see the README's
"Versioning" section for what a major/minor/patch release means for this
SDK specifically.

## [Unreleased]

### Added

- `@example` PHPDoc tag on every public `Resource` method, documenting a
  realistic call through the facade.
- `bin/generate-snippets` / `composer generate-snippets` — reflects those
  tags into a committed `docs/snippets.json` manifest, keyed by the real
  OpenAPI operationId each method wraps. Vendored by the platform repo to
  generate PHP code samples on the docs site, replacing cURL-only samples
  where a mapping exists.
- `SnippetCoverageTest` — CI fails if a public method has no `@example`, or
  if an existing `@example` no longer names the real accessor/method it
  documents (drift).

## [1.0.0] - 2026-07-22

Initial release. Generated DTOs and Saloon `Request` classes from the
platform's OpenAPI contract, wrapped by the hand-written `EcollectorsCloud`
facade — the only class SDK consumers import. API key and CloudUser token
authentication; typed exceptions per Problem Details error code; contract
version declaration; full test suite and CI.
