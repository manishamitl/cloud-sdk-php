<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudApiKeyIssuedSummary;
use Ecollectors\CloudSdk\Generated\CloudApiKeySummary;
use Ecollectors\CloudSdk\Generated\Requests\IssueApiKeyRequest;
use Ecollectors\CloudSdk\Generated\Requests\RevokeApiKeyRequest;
use Ecollectors\CloudSdk\Generated\Requests\RotateApiKeyRequest;
use Ecollectors\CloudSdk\Generated\Requests\UpdateApiKeyRequest;

final class ApiKeyResource extends Resource
{
    /**
     * The plaintext secret on the returned DTO is available exactly once —
     * the platform's own frozen §4.2 invariant. Store it now; it cannot be
     * retrieved again through any other call.
     *
     * @param  array{label?: ?string, expires_at?: ?string}  $data
     */
    public function issue(string $organisation, string $product, string $application, string $environment, array $data = []): CloudApiKeyIssuedSummary
    {
        return CloudApiKeyIssuedSummary::fromArray(
            $this->send(new IssueApiKeyRequest($organisation, $product, $application, $environment, $data))->json(),
        );
    }

    /**
     * @param  array{label: ?string}  $data
     */
    public function update(string $organisation, string $product, string $application, string $environment, string $key, array $data): CloudApiKeySummary
    {
        return CloudApiKeySummary::fromArray(
            $this->send(new UpdateApiKeyRequest($organisation, $product, $application, $environment, $key, $data))->json(),
        );
    }

    /**
     * Same reveal-once rule as `issue()` — this is the successor key's only
     * appearance in plaintext.
     *
     * @param  array{label?: ?string, scheduled_revocation_at?: ?string}  $data
     */
    public function rotate(string $organisation, string $product, string $application, string $environment, string $key, array $data = []): CloudApiKeyIssuedSummary
    {
        return CloudApiKeyIssuedSummary::fromArray(
            $this->send(new RotateApiKeyRequest($organisation, $product, $application, $environment, $key, $data))->json(),
        );
    }

    public function revoke(string $organisation, string $product, string $application, string $environment, string $key): CloudApiKeySummary
    {
        return CloudApiKeySummary::fromArray(
            $this->send(new RevokeApiKeyRequest($organisation, $product, $application, $environment, $key))->json(),
        );
    }
}
