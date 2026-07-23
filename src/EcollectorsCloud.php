<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk;

use Ecollectors\CloudSdk\Resources\ApiKeyResource;
use Ecollectors\CloudSdk\Resources\ApplicationResource;
use Ecollectors\CloudSdk\Resources\AuditLogResource;
use Ecollectors\CloudSdk\Resources\AuthResource;
use Ecollectors\CloudSdk\Resources\EnvironmentResource;
use Ecollectors\CloudSdk\Resources\KnowledgeResource;
use Ecollectors\CloudSdk\Resources\OrganisationResource;
use Ecollectors\CloudSdk\Resources\ProductResource;
use Ecollectors\CloudSdk\Resources\SearchResource;

/**
 * The ONLY class SDK consumers ever import. Everything under
 * `Ecollectors\CloudSdk\Generated` and `Ecollectors\CloudSdk\Resources`'
 * constructor argument (the connector) is internal — regenerating the
 * internals, or replacing the HTTP framework underneath this facade
 * entirely, is never a breaking change for anything built against this
 * class alone (M6-DOCS-SDKS-DESIGN.md §4.3).
 */
final class EcollectorsCloud
{
    private readonly EcollectorsCloudConnector $connector;

    private function __construct(string $baseUrl, string $token)
    {
        $this->connector = new EcollectorsCloudConnector($baseUrl, $token);
    }

    /**
     * `ec_{env}_…` — the M3 gateway data plane (Search, Knowledge Delivery).
     */
    public static function withApiKey(string $apiKey, string $baseUrl = 'https://api.ecollectors.cloud/v1'): self
    {
        return new self($baseUrl, $apiKey);
    }

    /**
     * A Sanctum token from `auth()->register()`/`auth()->login()` — the M4
     * self-service surface (Organisations, Products, Applications,
     * Environments, API Keys, the audit log).
     */
    public static function withUserToken(string $token, string $baseUrl = 'https://api.ecollectors.cloud/v1'): self
    {
        return new self($baseUrl, $token);
    }

    public function auth(): AuthResource
    {
        return new AuthResource($this->connector);
    }

    public function organisations(): OrganisationResource
    {
        return new OrganisationResource($this->connector);
    }

    public function products(): ProductResource
    {
        return new ProductResource($this->connector);
    }

    public function applications(): ApplicationResource
    {
        return new ApplicationResource($this->connector);
    }

    public function environments(): EnvironmentResource
    {
        return new EnvironmentResource($this->connector);
    }

    public function apiKeys(): ApiKeyResource
    {
        return new ApiKeyResource($this->connector);
    }

    public function auditLog(): AuditLogResource
    {
        return new AuditLogResource($this->connector);
    }

    public function search(): SearchResource
    {
        return new SearchResource($this->connector);
    }

    public function knowledge(): KnowledgeResource
    {
        return new KnowledgeResource($this->connector);
    }

    /**
     * The exact contract commit this SDK release was generated from
     * (M6-DOCS-SDKS-DESIGN.md §4.2's version-declaration guardrail).
     */
    public static function contractVersion(): string
    {
        return trim(file_get_contents(dirname(__DIR__).'/contract/VERSION'));
    }
}
