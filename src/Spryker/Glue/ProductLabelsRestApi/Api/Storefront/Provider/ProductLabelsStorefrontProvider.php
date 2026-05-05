<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\ProductLabelsRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\ProductLabelsStorefrontResource;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class ProductLabelsStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string MAPPING_TYPE_SKU = 'sku';

    protected const string KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    protected const string URI_VAR_ID = 'idProductLabel';

    protected const string URI_VAR_SKU = 'sku';

    public function __construct(
        protected ProductLabelStorageClientInterface $productLabelStorageClient,
        protected ProductStorageClientInterface $productStorageClient,
    ) {
    }

    protected function provideItem(): ?object
    {
        if (!$this->hasUriVariable(static::URI_VAR_ID)) {
            return null;
        }

        $idProductLabel = (string)$this->getUriVariable(static::URI_VAR_ID);

        if ($idProductLabel === '') {
            return null;
        }

        $localeName = $this->getLocale()->getLocaleNameOrFail();
        $storeName = $this->getStore()->getNameOrFail();

        $labels = $this->productLabelStorageClient->findLabels([$idProductLabel], $localeName, $storeName);

        if ($labels === []) {
            return null;
        }

        return $this->mapLabelToResource(reset($labels));
    }

    /**
     * @return array<\Generated\Api\Storefront\ProductLabelsStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $uriVariables = $this->getUriVariables();

        if (!isset($uriVariables[static::URI_VAR_SKU])) {
            return [];
        }

        $abstractProductSku = (string)$uriVariables[static::URI_VAR_SKU];
        $localeName = $this->getLocale()->getLocaleNameOrFail();
        $storeName = $this->getStore()->getNameOrFail();

        $abstractData = $this->productStorageClient->findProductAbstractStorageDataByMapping(
            static::MAPPING_TYPE_SKU,
            $abstractProductSku,
            $localeName,
        );

        if ($abstractData === null) {
            return [];
        }

        $labels = $this->productLabelStorageClient->findLabelsByIdProductAbstract(
            (int)($abstractData[static::KEY_ID_PRODUCT_ABSTRACT] ?? 0),
            $localeName,
            $storeName,
        );

        return $this->mapLabelsToResources($labels);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer> $labels
     *
     * @return array<\Generated\Api\Storefront\ProductLabelsStorefrontResource>
     */
    protected function mapLabelsToResources(array $labels): array
    {
        $resources = [];
        foreach ($labels as $label) {
            $resources[] = $this->mapLabelToResource($label);
        }

        return $resources;
    }

    protected function mapLabelToResource(ProductLabelDictionaryItemTransfer $label): ProductLabelsStorefrontResource
    {
        $resource = new ProductLabelsStorefrontResource();
        $resource->idProductLabel = (string)$label->getIdProductLabel();
        $resource->name = $label->getName();
        $resource->isExclusive = (bool)$label->getIsExclusive();
        $resource->position = (int)$label->getPosition();
        $resource->frontEndReference = $label->getFrontEndReference();

        return $resource;
    }
}
