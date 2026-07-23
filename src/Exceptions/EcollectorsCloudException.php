<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Exceptions;

/**
 * Base of every exception this SDK throws — one per RFC 7807 `code` the
 * platform's `ProblemDetails` contract can return, mirroring
 * `App\Core\Contract\Enums\ProblemCode` in the platform repo without any
 * runtime dependency on it. Hand-written, never generated: the platform's
 * error codes are a stable, slow-changing contract (C-4: additive only),
 * unlike the operation/schema shapes a spec change touches constantly.
 */
abstract class EcollectorsCloudException extends \RuntimeException
{
    public function __construct(
        public readonly string $problemCode,
        public readonly int $status,
        public readonly ?string $detail,
        public readonly ?string $docsUrl,
    ) {
        parent::__construct($detail ?? $problemCode);
    }

    /**
     * @param  array<string, mixed>  $problemDetails  The parsed application/problem+json body.
     */
    public static function fromProblemDetails(array $problemDetails, int $status): self
    {
        $code = (string) ($problemDetails['code'] ?? 'internal_error');
        $detail = isset($problemDetails['detail']) ? (string) $problemDetails['detail'] : null;
        $docsUrl = isset($problemDetails['docs_url']) ? (string) $problemDetails['docs_url'] : null;

        $class = match ($code) {
            'not_found' => NotFoundException::class,
            'validation_failed' => ValidationException::class,
            'unauthenticated' => UnauthenticatedException::class,
            'rate_limited' => RateLimitedException::class,
            'key_revoked' => KeyRevokedException::class,
            'key_expired' => KeyExpiredException::class,
            'access_suspended' => AccessSuspendedException::class,
            'feature_not_available' => FeatureNotAvailableException::class,
            'service_unavailable' => ServiceUnavailableException::class,
            'conflict' => ConflictException::class,
            default => InternalErrorException::class,
        };

        return new $class($code, $status, $detail, $docsUrl);
    }
}
