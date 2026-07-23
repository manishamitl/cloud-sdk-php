<?php

declare(strict_types=1);

use Ecollectors\CloudSdk\EcollectorsCloud;

/**
 * M6-DOCS-SDKS-DESIGN.md §4.2 — every SDK release declares the exact
 * contract version it was generated from.
 */
it('reports the exact contract commit this release was generated from', function () {
    $version = EcollectorsCloud::contractVersion();
    $pinned = trim(file_get_contents(__DIR__.'/../contract/VERSION'));

    expect($version)->toBe($pinned)
        ->and($version)->toMatch('/^[0-9a-f]{40}$/');
});
