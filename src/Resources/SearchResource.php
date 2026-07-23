<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\Requests\ExplainSearchRequest;
use Ecollectors\CloudSdk\Generated\Requests\SearchRequest;
use Ecollectors\CloudSdk\Generated\SearchEnvelope;
use Ecollectors\CloudSdk\Generated\SearchExplainEnvelope;

final class SearchResource extends Resource
{
    /**
     * @param  list<string>|null  $filter
     *
     * @example $client->search()->query('kgv half rupee 1912');
     */
    public function query(string $q, ?string $type = null, ?array $filter = null, ?string $cursor = null, ?int $limit = null): SearchEnvelope
    {
        return SearchEnvelope::fromArray($this->send(new SearchRequest($q, $type, $filter, $cursor, $limit))->json());
    }

    /**
     * @param  list<string>|null  $filter
     *
     * @example $client->search()->explain('kgv half rupee 1912', 'variant', 'KGV1R-1911-CAL');
     */
    public function explain(string $q, string $type, string $identity, ?array $filter = null): SearchExplainEnvelope
    {
        return SearchExplainEnvelope::fromArray($this->send(new ExplainSearchRequest($q, $type, $identity, $filter))->json());
    }
}
