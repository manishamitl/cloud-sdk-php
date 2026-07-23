<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

/**
 * M6.2 — one schema property, reduced to exactly what the DTO template
 * needs: its PHP-side name, PHP type, and whether it's nullable. OpenAPI
 * 3.1 nullability is `type: [X, "null"]` (never the removed 3.0 `nullable`
 * keyword — this contract doesn't use it, having been fixed to 3.1 syntax
 * during M6.1).
 */
final readonly class NormalizedProperty
{
    public function __construct(
        public string $name,
        public string $phpType,
        public bool $nullable,
    ) {}

    /**
     * @param  array<array-key, mixed>  $definition
     */
    public static function fromDefinition(string $name, array $definition): self
    {
        $type = $definition['type'] ?? 'string';
        $types = is_array($type) ? $type : [$type];
        $nullable = in_array('null', $types, true);
        $openApiType = array_values(array_filter($types, fn (string $t): bool => $t !== 'null'))[0] ?? 'string';

        return new self($name, self::phpType($openApiType), $nullable);
    }

    private static function phpType(string $openApiType): string
    {
        return match ($openApiType) {
            'integer' => 'int',
            'number' => 'float',
            'boolean' => 'bool',
            'array' => 'array',
            'object' => 'array',
            default => 'string',
        };
    }

    public function phpTypeDeclaration(): string
    {
        return $this->nullable ? "?{$this->phpType}" : $this->phpType;
    }
}
