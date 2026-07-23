<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\Generator\SnippetExtractor;

/**
 * M6.2 Phase 2 — examples are part of the SDK's public API contract, not
 * incidental documentation (docs/design/M6.2-PHASE2-SDK-DOCS-CHECKLIST.md,
 * platform repo). Two failure modes, not one: an example that disappears
 * (no @example tag) and an example that drifts (still present, but no
 * longer names the real accessor/method it sits above).
 */
it('extracts exactly one entry per public Resource method, matching CoverageTest\'s operationId set', function () {
    $loader = new Ecollectors\CloudSdk\Generator\ContractLoader(__DIR__.'/../contract/openapi.yaml');
    $specOperationIds = array_map(fn (array $op): string => $op['operationId'], $loader->operations());

    $entries = SnippetExtractor::extract();
    $extractedOperationIds = array_map(fn (array $e): string => $e['operationId'], $entries);

    sort($specOperationIds);
    sort($extractedOperationIds);

    expect($extractedOperationIds)->toBe($specOperationIds);
});

it('every extracted example actually starts with its own resource()->method( call', function () {
    foreach (SnippetExtractor::extract() as $entry) {
        $expectedPrefix = sprintf('$client->%s()->%s(', $entry['resource'], $entry['method']);
        expect($entry['example'])->toStartWith($expectedPrefix);
    }
});

it('reads the @example tag text after the tag, trimmed', function () {
    $doc = <<<'DOC'
    /**
     * @param  string  $q
     *
     * @example $client->search()->query('kgv half rupee 1912');
     */
    DOC;

    expect(SnippetExtractor::exampleTag($doc))->toBe("\$client->search()->query('kgv half rupee 1912');");
});

it('returns null when a docblock has no @example tag', function () {
    $doc = <<<'DOC'
    /**
     * @param  string  $q
     */
    DOC;

    expect(SnippetExtractor::exampleTag($doc))->toBeNull();
});

it('returns null when there is no docblock at all', function () {
    expect(SnippetExtractor::exampleTag(false))->toBeNull();
});

it('rejects an example that names the wrong accessor or method (drift)', function () {
    $method = new ReflectionMethod(Ecollectors\CloudSdk\Resources\SearchResource::class, 'query');

    SnippetExtractor::assertNotDrifted(
        Ecollectors\CloudSdk\Resources\SearchResource::class,
        $method,
        'search',
        "\$client->search()->explain('wrong method here');",
    );
})->throws(RuntimeException::class, "expected it to start with `\$client->search()->query(`");

it('accepts an example that correctly names its own accessor and method', function () {
    $method = new ReflectionMethod(Ecollectors\CloudSdk\Resources\SearchResource::class, 'query');

    SnippetExtractor::assertNotDrifted(
        Ecollectors\CloudSdk\Resources\SearchResource::class,
        $method,
        'search',
        "\$client->search()->query('kgv half rupee 1912');",
    );
})->throwsNoExceptions();
