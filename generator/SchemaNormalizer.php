<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

/**
 * M6.2 — flattens one already-$ref-resolved schema (from `ContractLoader`)
 * into a single `{properties, required}` shape, merging `allOf` members.
 * Not a general JSON Schema engine: handles exactly the shapes this
 * contract actually uses (plain objects, `allOf` composition, OpenAPI 3.1
 * `type: [X, "null"]` nullability) — nothing this contract doesn't need.
 */
final class SchemaNormalizer
{
    /**
     * @param  array<array-key, mixed>  $schema
     * @return array{properties: array<string, NormalizedProperty>, required: list<string>}
     */
    public static function normalize(array $schema): array
    {
        if (isset($schema['allOf'])) {
            $properties = [];
            $required = [];

            foreach ($schema['allOf'] as $member) {
                $normalized = self::normalize($member);
                $properties = [...$properties, ...$normalized['properties']];
                $required = [...$required, ...$normalized['required']];
            }

            return ['properties' => $properties, 'required' => array_values(array_unique($required))];
        }

        $required = $schema['required'] ?? [];
        $properties = [];

        foreach ($schema['properties'] ?? [] as $name => $definition) {
            $properties[$name] = NormalizedProperty::fromDefinition($name, $definition);
        }

        return ['properties' => $properties, 'required' => $required];
    }
}
