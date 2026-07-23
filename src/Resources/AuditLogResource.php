<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudAuditEventPage;
use Ecollectors\CloudSdk\Generated\Requests\GetOrganisationAuditLogRequest;

final class AuditLogResource extends Resource
{
    public function list(string $organisation, ?string $cursor = null, ?int $limit = null): CloudAuditEventPage
    {
        return CloudAuditEventPage::fromArray(
            $this->send(new GetOrganisationAuditLogRequest($organisation, $cursor, $limit))->json(),
        );
    }
}
