<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\EcollectorsCloudConnector;
use Ecollectors\CloudSdk\Exceptions\EcollectorsCloudException;
use Saloon\Http\Request;
use Saloon\Http\Response;

/**
 * Shared send/error-unwrap logic every concrete Resource composes. Not
 * itself part of the guardrailed "no generated class in the public API"
 * rule — this is hand-written, but its `send()` return type stays internal
 * (`Response`) precisely so concrete Resource methods are the ones
 * responsible for mapping to a generated DTO or throwing, never leaking a
 * raw Saloon Response either.
 */
abstract class Resource
{
    public function __construct(protected readonly EcollectorsCloudConnector $connector) {}

    /**
     * @throws EcollectorsCloudException
     */
    protected function send(Request $request): Response
    {
        $response = $this->connector->send($request);

        if ($response->failed()) {
            $problem = $response->json();

            throw EcollectorsCloudException::fromProblemDetails(
                is_array($problem) ? $problem : [],
                $response->status(),
            );
        }

        return $response;
    }
}
