<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

/**
 * M6.2 — the template layer: turns one normalized schema into PHP source
 * for a readonly DTO. Property names are kept exactly as the API returns
 * them (snake_case) — no name-mapping table to own or drift out of sync.
 */
final class DtoEmitter
{
    /**
     * @param  array{properties: array<string, NormalizedProperty>, required: list<string>}  $normalized
     */
    public static function emit(string $className, array $normalized): string
    {
        $properties = $normalized['properties'];
        $required = $normalized['required'];

        $constructorLines = [];
        $fromArrayLines = [];

        foreach ($properties as $name => $property) {
            $isRequired = in_array($name, $required, true);
            $declaration = $isRequired && ! $property->nullable
                ? $property->phpType
                : '?'.$property->phpType;

            $constructorLines[] = "        public {$declaration} \${$name},";

            $fromArrayLines[] = $isRequired
                ? "            {$name}: \$data['{$name}'],"
                : "            {$name}: \$data['{$name}'] ?? null,";
        }

        $constructorBody = implode("\n", $constructorLines);
        $fromArrayBody = implode("\n", $fromArrayLines);

        return <<<PHP
        <?php

        declare(strict_types=1);

        // GENERATED — do not edit by hand. Run `composer generate` to rebuild.
        // Source: contract/openapi.yaml, components.schemas.{$className}

        namespace Ecollectors\CloudSdk\Generated;

        final readonly class {$className}
        {
            public function __construct(
        {$constructorBody}
            ) {}

            /**
             * @param  array<string, mixed>  \$data
             */
            public static function fromArray(array \$data): self
            {
                return new self(
        {$fromArrayBody}
                );
            }
        }

        PHP;
    }
}
