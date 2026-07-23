<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudUserAuthEnvelope;
use Ecollectors\CloudSdk\Generated\Requests\LoginCloudUserRequest;
use Ecollectors\CloudSdk\Generated\Requests\RegisterCloudUserRequest;

final class AuthResource extends Resource
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function register(array $data): CloudUserAuthEnvelope
    {
        return CloudUserAuthEnvelope::fromArray($this->send(new RegisterCloudUserRequest($data))->json());
    }

    /**
     * @param  array{email: string, password: string}  $data
     */
    public function login(array $data): CloudUserAuthEnvelope
    {
        return CloudUserAuthEnvelope::fromArray($this->send(new LoginCloudUserRequest($data))->json());
    }
}
