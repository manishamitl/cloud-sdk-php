<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Generator;

use Ecollectors\CloudSdk\EcollectorsCloud;
use Ecollectors\CloudSdk\Resources\ApiKeyResource;
use Ecollectors\CloudSdk\Resources\ApplicationResource;
use Ecollectors\CloudSdk\Resources\AuditLogResource;
use Ecollectors\CloudSdk\Resources\AuthResource;
use Ecollectors\CloudSdk\Resources\EnvironmentResource;
use Ecollectors\CloudSdk\Resources\KnowledgeResource;
use Ecollectors\CloudSdk\Resources\OrganisationResource;
use Ecollectors\CloudSdk\Resources\ProductResource;
use Ecollectors\CloudSdk\Resources\SearchResource;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;

/**
 * M6.2 Phase 2 — reflects every `Resource` class's public methods and their
 * `@example` PHPDoc tag into `docs/snippets.json`, keyed by the real
 * OpenAPI operationId each method wraps. This is the ONLY place PHP
 * snippets are produced from: the manifest is a build output, never
 * hand-edited, but the `@example` text itself is deliberately NOT
 * mechanically derived (M6-DOCS-SDKS-DESIGN.md §4.1) — the facade's
 * resource/method names and argument shape must stay free to evolve
 * independently of the contract. See
 * docs/design/M6.2-PHASE2-SDK-DOCS-CHECKLIST.md (platform repo) §2.
 */
final class SnippetExtractor
{
    private const RESOURCE_CLASSES = [
        AuthResource::class,
        OrganisationResource::class,
        ProductResource::class,
        ApplicationResource::class,
        EnvironmentResource::class,
        ApiKeyResource::class,
        AuditLogResource::class,
        SearchResource::class,
        KnowledgeResource::class,
    ];

    /**
     * @return list<array{operationId: string, resource: string, method: string, example: string}>
     *
     * @throws RuntimeException When an example is missing, or has drifted
     *                           from the method it documents.
     */
    public static function extract(): array
    {
        $accessors = self::resourceAccessors();
        $entries = [];

        foreach (self::RESOURCE_CLASSES as $class) {
            $reflection = new ReflectionClass($class);
            $accessor = $accessors[$class] ?? throw new RuntimeException(
                "No EcollectorsCloud accessor method returns {$class}."
            );

            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                if ($method->isConstructor() || $method->getDeclaringClass()->getName() !== $class) {
                    continue;
                }

                $example = self::exampleTag($method->getDocComment());

                if ($example === null) {
                    throw new RuntimeException(sprintf(
                        '%s::%s() has no @example tag. Every public Resource method must document '.
                        'a realistic call — see docs/design/M6.2-PHASE2-SDK-DOCS-CHECKLIST.md §2.',
                        $class,
                        $method->getName(),
                    ));
                }

                self::assertNotDrifted($class, $method, $accessor, $example);

                $entries[] = [
                    'operationId' => self::operationIdFor($class, $method),
                    'resource' => $accessor,
                    'method' => $method->getName(),
                    'example' => $example,
                ];
            }
        }

        return $entries;
    }

    /**
     * Extracts the text after `@example` on its own line. Public so it can
     * be unit-tested directly against synthetic docblocks, without needing
     * a real (or fake) `Resource` class.
     */
    public static function exampleTag(string|false $docComment): ?string
    {
        if ($docComment === false) {
            return null;
        }

        if (! preg_match('/@example\s+(.+)/', $docComment, $matches)) {
            return null;
        }

        return trim($matches[1]);
    }

    /**
     * The one drift check a machine CAN make without executing the
     * example: it must open with a call through the real accessor and the
     * real method name, catching the common case (a rename left the
     * example showing the old name) without needing a live HTTP mock.
     *
     * @param  class-string  $class
     */
    public static function assertNotDrifted(string $class, ReflectionMethod $method, string $accessor, string $example): void
    {
        $expectedPrefix = sprintf('$client->%s()->%s(', $accessor, $method->getName());

        if (! str_starts_with($example, $expectedPrefix)) {
            throw new RuntimeException(sprintf(
                "%s::%s()'s @example has drifted from the method it documents: expected it to start ".
                "with `%s`, got `%s`. Update the @example in the same change that renamed this.",
                $class,
                $method->getName(),
                $expectedPrefix,
                $example,
            ));
        }
    }

    /**
     * @return array<class-string, string> Resource class => EcollectorsCloud accessor method name.
     */
    private static function resourceAccessors(): array
    {
        $reflection = new ReflectionClass(EcollectorsCloud::class);
        $map = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $returnType = $method->getReturnType();

            if ($returnType instanceof ReflectionNamedType
                && ! $returnType->isBuiltin()
                && in_array($returnType->getName(), self::RESOURCE_CLASSES, true)
            ) {
                $map[$returnType->getName()] = $method->getName();
            }
        }

        return $map;
    }

    /**
     * @param  class-string  $class
     */
    private static function operationIdFor(string $class, ReflectionMethod $method): string
    {
        $source = self::methodSource($method);

        if (! preg_match('/new\s+(\w+Request)\(/', $source, $matches)) {
            throw new RuntimeException("{$class}::{$method->getName()}() does not construct a generated Request class.");
        }

        /** @var class-string $requestClass */
        $requestClass = 'Ecollectors\\CloudSdk\\Generated\\Requests\\'.$matches[1];

        return $requestClass::OPERATION_ID;
    }

    private static function methodSource(ReflectionMethod $method): string
    {
        $filename = $method->getFileName();

        if ($filename === false) {
            return '';
        }

        $lines = file($filename);
        $start = $method->getStartLine();
        $end = $method->getEndLine();

        if ($lines === false || $start === false || $end === false) {
            return '';
        }

        return implode('', array_slice($lines, $start - 1, $end - $start + 1));
    }
}
