<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductLabelStorageClientBridge;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToProductStorageClientBridge;
use Spryker\Glue\ProductLabelsRestApi\Dependency\Client\ProductLabelsRestApiToStoreClientBridge;

/**
 * @method \Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig getConfig()
 */
class ProductLabelsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_LABEL_STORAGE = 'CLIENT_PRODUCT_LABEL_STORAGE';

    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addProductStorageClientDependency($container);
        $container = $this->addProductLabelStorageClientDependency($container);

        $container = $this->addStoreClient($container);

        return $container;
    }

    protected function addProductStorageClientDependency(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new ProductLabelsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client(),
            );
        });

        return $container;
    }

    protected function addProductLabelStorageClientDependency(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_LABEL_STORAGE, function (Container $container) {
            return new ProductLabelsRestApiToProductLabelStorageClientBridge(
                $container->getLocator()->productLabelStorage()->client(),
            );
        });

        return $container;
    }

    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new ProductLabelsRestApiToStoreClientBridge(
                $container->getLocator()->store()->client(),
            );
        });

        return $container;
    }
}
