<?php

declare(strict_types=1);

namespace Ecollectors\CloudSdk\Resources;

use Ecollectors\CloudSdk\Generated\CloudProductSummary;
use Ecollectors\CloudSdk\Generated\Requests\ArchiveProductRequest;
use Ecollectors\CloudSdk\Generated\Requests\CreateProductRequest;
use Ecollectors\CloudSdk\Generated\Requests\RestoreProductRequest;
use Ecollectors\CloudSdk\Generated\Requests\UpdateProductRequest;

final class ProductResource extends Resource
{
    /**
     * @param  array{name: string, slug?: ?string}  $data
     */
    public function create(string $organisation, array $data): CloudProductSummary
    {
        return CloudProductSummary::fromArray($this->send(new CreateProductRequest($organisation, $data))->json());
    }

    /**
     * @param  array{name?: string, slug?: string}  $data
     */
    public function update(string $organisation, string $product, array $data): CloudProductSummary
    {
        return CloudProductSummary::fromArray($this->send(new UpdateProductRequest($organisation, $product, $data))->json());
    }

    public function archive(string $organisation, string $product): CloudProductSummary
    {
        return CloudProductSummary::fromArray($this->send(new ArchiveProductRequest($organisation, $product))->json());
    }

    public function restore(string $organisation, string $product): CloudProductSummary
    {
        return CloudProductSummary::fromArray($this->send(new RestoreProductRequest($organisation, $product))->json());
    }
}
