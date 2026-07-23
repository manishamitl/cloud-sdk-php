<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

/**
 * M6.2 — the template layer for the second generated artefact: one Saloon
 * `Request` class per contract operation. Path parameters are ALWAYS
 * treated as required for URL construction, regardless of what the
 * contract's `required` field says on that parameter — a path segment is
 * structurally mandatory either way (a real, pre-existing contract defect
 * on `/hierarchy/{node_slug_path}` says `required: false`; this generator
 * doesn't propagate that mistake into broken generated code).
 *
 * Every emitted class exposes its source `operationId` via a constant,
 * never via the class name alone — the coverage test reads that constant,
 * never the class name, so renaming a generated class is never a contract
 * change.
 */
final class RequestEmitter
{
    /**
     * @param  array{operationId: string, method: string, path: string, tags: list<string>, definition: array<array-key, mixed>}  $operation
     */
    public static function emit(string $className, array $operation): string
    {
        $definition = $operation['definition'];
        $pathParams = [];
        $queryParams = [];

        foreach ($definition['parameters'] ?? [] as $parameter) {
            if (($parameter['in'] ?? null) === 'path') {
                $pathParams[] = $parameter['name'];
            } elseif (($parameter['in'] ?? null) === 'query') {
                $queryParams[] = $parameter['name'];
            }
        }

        $hasBody = isset($definition['requestBody']);

        $constructorParams = [
            ...array_map(fn (string $name): string => "        public readonly string \${$name},", $pathParams),
            ...array_map(fn (string $name): string => "        public readonly mixed \${$name} = null,", $queryParams),
            ...($hasBody ? ['        public readonly array $data = [],'] : []),
        ];
        $constructorBody = implode("\n", $constructorParams);

        $endpoint = $operation['path'];
        foreach ($pathParams as $name) {
            $endpoint = str_replace('{'.$name.'}', '{$this->'.$name.'}', $endpoint);
        }

        $queryBody = $queryParams === [] ? '' : self::emitDefaultQuery($queryParams);
        $bodyInterface = $hasBody ? ' implements HasBody' : '';
        $bodyTrait = $hasBody ? "\n    use HasJsonBody;\n" : '';
        $bodyMethod = $hasBody ? self::emitDefaultBody() : '';
        $bodyImports = $hasBody
            ? "use Saloon\Contracts\Body\HasBody;\nuse Saloon\Traits\Body\HasJsonBody;\n"
            : '';

        $method = strtoupper($operation['method']);

        return <<<PHP
        <?php

        declare(strict_types=1);

        // GENERATED — do not edit by hand. Run `composer generate` to rebuild.
        // Source: contract/openapi.yaml, operationId: {$operation['operationId']}

        namespace Ecollectors\CloudSdk\Generated\Requests;

        {$bodyImports}use Saloon\Enums\Method;
        use Saloon\Http\Request;

        final class {$className} extends Request{$bodyInterface}
        {{$bodyTrait}
            public const OPERATION_ID = '{$operation['operationId']}';

            protected Method \$method = Method::{$method};

            public function __construct(
        {$constructorBody}
            ) {}

            public function resolveEndpoint(): string
            {
                return "{$endpoint}";
            }
        {$queryBody}{$bodyMethod}}

        PHP;
    }

    /**
     * @param  list<string>  $queryParams
     */
    private static function emitDefaultQuery(array $queryParams): string
    {
        $lines = implode("\n", array_map(
            fn (string $name): string => "                '{$name}' => \$this->{$name},",
            $queryParams,
        ));

        return <<<PHP


            protected function defaultQuery(): array
            {
                return array_filter([
        {$lines}
                ], fn (mixed \$value): bool => \$value !== null);
            }

        PHP;
    }

    private static function emitDefaultBody(): string
    {
        return <<<PHP


            protected function defaultBody(): array
            {
                return \$this->data;
            }

        PHP;
    }
}
