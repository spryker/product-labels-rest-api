<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToStoreClientInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelByProductConcreteSkuExpander;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelByProductConcreteSkuExpanderInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelResourceRelationshipExpander;
use Spryker\Glue\ProductLabelsRestApi\Processor\Expander\ProductLabelResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapper;
use Spryker\Glue\ProductLabelsRestApi\Processor\Mapper\ProductLabelMapperInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReader;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface;

class ProductLabelsRestApiFactory extends AbstractFactory
{
    public function createProductLabelReader(): ProductLabelReaderInterface
    {
        return new ProductLabelReader(
            $this->getProductLabelStorageClient(),
            $this->createProductLabelMapper(),
            $this->getResourceBuilder(),
            $this->getProductStorageClient(),
            $this->getStoreClient(),
        );
    }

    public function createProductLabelMapper(): ProductLabelMapperInterface
    {
        return new ProductLabelMapper();
    }

    public function createProductLabelResourceRelationshipExpander(): ProductLabelResourceRelationshipExpanderInterface
    {
        return new ProductLabelResourceRelationshipExpander($this->createProductLabelReader());
    }

    public function getProductStorageClient(): ProductLabelsRestApiToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelsRestApiDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    public function getProductLabelStorageClient(): ProductLabelsRestApiToProductLabelStorageClientInterface
    {
        return $this->getProvidedDependency(ProductLabelsRestApiDependencyProvider::CLIENT_PRODUCT_LABEL_STORAGE);
    }

    public function createProductLabelByProductConcreteSkuExpander(): ProductLabelByProductConcreteSkuExpanderInterface
    {
        return new ProductLabelByProductConcreteSkuExpander($this->createProductLabelReader());
    }

    public function getStoreClient(): ProductLabelsRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductLabelsRestApiDependencyProvider::CLIENT_STORE);
    }
}
