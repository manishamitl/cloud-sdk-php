<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk;

use Saloon\Contracts\Authenticator;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

/**
 * Internal — never imported by SDK consumers directly (the facade is the
 * only public entry point). `ApiKeyAuth` and `CloudUserAuth` share the same
 * wire protocol (`Authorization: Bearer …`); this connector doesn't care
 * which kind of token it's holding, only that it has one.
 *
 * @internal
 */
final class EcollectorsCloudConnector extends Connector
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $token,
    ) {}

    public function resolveBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new TokenAuthenticator($this->token);
    }
}
