<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

use Symfony\Component\Yaml\Yaml;

/**
 * M6.2 — the ONLY OpenAPI-parsing logic this SDK owns: load the YAML and
 * resolve internal `$ref` pointers. Everything else (schema normalisation,
 * PHP class emission) is a separate, narrow layer built on top of this.
 *
 * Deliberately not `cebe/php-openapi`: no stable release supports OpenAPI
 * 3.1, which this contract is declared as (verified against the actual
 * vendored spec before this decision was made, not assumed). `symfony/yaml`
 * plus this `$ref` resolver — ported from the platform repo's own
 * `App\Core\Contract\Support\OpenApiSpecValidator`, already proven in
 * production — is the whole parsing surface this package maintains.
 */
final class ContractLoader
{
    /** @var array<array-key, mixed> */
    private readonly array $document;

    public function __construct(string $path)
    {
        $parsed = Yaml::parseFile($path);

        if (! is_array($parsed)) {
            throw new \RuntimeException("{$path} did not parse to a YAML mapping.");
        }

        $this->document = $parsed;
    }

    /**
     * @return array<string, array<array-key, mixed>> Schema name => fully $ref-resolved definition.
     */
    public function schemas(): array
    {
        $schemas = $this->document['components']['schemas'] ?? [];

        return array_map(fn (array $schema): array => $this->resolveDeep($schema), $schemas);
    }

    /**
     * @return list<array{operationId: string, method: string, path: string, tags: list<string>}>
     */
    public function operations(): array
    {
        $operations = [];

        foreach ($this->document['paths'] ?? [] as $path => $pathItem) {
            foreach ($pathItem as $method => $operation) {
                if (! is_array($operation) || ! isset($operation['operationId'])) {
                    continue; // e.g. shared `parameters` at the path-item level, not an HTTP method.
                }

                $operations[] = [
                    'operationId' => $operation['operationId'],
                    'method' => strtoupper((string) $method),
                    'path' => (string) $path,
                    'tags' => $operation['tags'] ?? [],
                    'definition' => $this->resolveDeep($operation),
                ];
            }
        }

        return $operations;
    }

    /**
     * @param  array<array-key, mixed>  $node
     * @return array<array-key, mixed>
     */
    private function resolveDeep(array $node): array
    {
        if (isset($node['$ref']) && is_string($node['$ref'])) {
            $resolved = $this->resolve($node['$ref']);

            return is_array($resolved) ? $this->resolveDeep($resolved) : [];
        }

        $result = [];

        foreach ($node as $key => $value) {
            $result[$key] = is_array($value) ? $this->resolveDeep($value) : $value;
        }

        return $result;
    }

    /**
     * Ported from OpenApiSpecValidator::resolve() — same algorithm, same
     * JSON-pointer segment unescaping, proven in the platform repo's own
     * test suite.
     */
    private function resolve(string $ref): mixed
    {
        if (! str_starts_with($ref, '#/')) {
            throw new \RuntimeException("External \$ref not supported: {$ref}");
        }

        $segments = array_filter(explode('/', substr($ref, 2)), fn (string $s): bool => $s !== '');

        $current = $this->document;

        foreach ($segments as $segment) {
            $segment = str_replace(['~1', '~0'], ['/', '~'], $segment);

            if (! is_array($current) || ! array_key_exists($segment, $current)) {
                throw new \RuntimeException("Unresolved \$ref: {$ref}");
            }

            $current = $current[$segment];
        }

        return $current;
    }
}
