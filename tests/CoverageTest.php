<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\Generator\ContractLoader;

/**
 * M6-DOCS-SDKS-DESIGN.md §4.1 — the non-negotiable regression test: every
 * generated Request's declared OPERATION_ID must exist in the pinned
 * contract, and every contract operationId must have a Request class.
 * Inspects Request classes' OPERATION_ID constants, never the facade's own
 * method names — those stay free to read however is ergonomic (§4.1's own
 * distinction, driven by the owner's explicit review).
 */
it('has a Request class for every operationId in the pinned contract, and vice versa', function () {
    $loader = new ContractLoader(__DIR__.'/../contract/openapi.yaml');
    $specOperationIds = array_map(fn (array $op): string => $op['operationId'], $loader->operations());

    $requestFiles = glob(__DIR__.'/../src/Generated/Requests/*.php');
    $requestOperationIds = [];

    foreach ($requestFiles as $file) {
        $class = 'Ecollectors\\CloudSdk\\Generated\\Requests\\'.basename($file, '.php');
        $requestOperationIds[] = $class::OPERATION_ID;
    }

    sort($specOperationIds);
    sort($requestOperationIds);

    expect($requestOperationIds)->toBe($specOperationIds);
});

/**
 * M6-DOCS-SDKS-DESIGN.md §4.3 — no generated class in the stable public
 * API. Walks every public method on the facade and every Resource,
 * confirming no parameter or return type ever names a generated Request
 * class or the internal Connector.
 */
it('never exposes a generated Request class or the Connector through the public API', function () {
    $publicClasses = [
        Ecollectors\CloudSdk\EcollectorsCloud::class,
        Ecollectors\CloudSdk\Resources\AuthResource::class,
        Ecollectors\CloudSdk\Resources\OrganisationResource::class,
        Ecollectors\CloudSdk\Resources\ProductResource::class,
        Ecollectors\CloudSdk\Resources\ApplicationResource::class,
        Ecollectors\CloudSdk\Resources\EnvironmentResource::class,
        Ecollectors\CloudSdk\Resources\ApiKeyResource::class,
        Ecollectors\CloudSdk\Resources\AuditLogResource::class,
        Ecollectors\CloudSdk\Resources\SearchResource::class,
        Ecollectors\CloudSdk\Resources\KnowledgeResource::class,
    ];

    foreach ($publicClasses as $class) {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor()) {
                continue;
            }

            $returnType = (string) $method->getReturnType();
            expect($returnType)->not->toContain('Generated\Requests')
                ->and($returnType)->not->toContain('EcollectorsCloudConnector');

            foreach ($method->getParameters() as $parameter) {
                $parameterType = (string) $parameter->getType();
                expect($parameterType)->not->toContain('Generated\Requests')
                    ->and($parameterType)->not->toContain('EcollectorsCloudConnector');
            }
        }
    }
});
