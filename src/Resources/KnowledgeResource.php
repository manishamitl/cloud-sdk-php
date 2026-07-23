<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CatalogueEntryPayload;
use Ecollectors\CloudSdk\Generated\HierarchyPayload;
use Ecollectors\CloudSdk\Generated\Requests\GetCatalogueEntryRequest;
use Ecollectors\CloudSdk\Generated\Requests\GetHierarchyRequest;
use Ecollectors\CloudSdk\Generated\Requests\GetVariantRequest;
use Ecollectors\CloudSdk\Generated\VariantPayload;

final class KnowledgeResource extends Resource
{
    public function variant(string $businessIdentifier, ?string $include = null): VariantPayload
    {
        return VariantPayload::fromArray($this->send(new GetVariantRequest($businessIdentifier, $include))->json());
    }

    public function catalogueEntry(string $entryNumber): CatalogueEntryPayload
    {
        return CatalogueEntryPayload::fromArray($this->send(new GetCatalogueEntryRequest($entryNumber))->json());
    }

    /**
     * `$path` defaults to an empty string for the hierarchy root — a known
     * limitation traced to a pre-existing contract defect
     * (`/hierarchy/{node_slug_path}` declares `required: false` on a path
     * parameter, which OpenAPI never actually permits; the generator
     * therefore always includes the segment). Root browsing through this
     * SDK is not guaranteed until that's addressed in the platform's own
     * contract — this is not something the SDK can correct on its own.
     */
    public function hierarchy(
        string $path = '',
        ?string $cursor = null,
        ?int $limit = null,
        ?string $entriesCursor = null,
        ?int $entriesLimit = null,
    ): HierarchyPayload {
        return HierarchyPayload::fromArray(
            $this->send(new GetHierarchyRequest($path, $cursor, $limit, $entriesCursor, $entriesLimit))->json(),
        );
    }
}
