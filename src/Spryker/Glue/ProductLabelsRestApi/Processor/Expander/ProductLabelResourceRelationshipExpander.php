<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface;

class ProductLabelResourceRelationshipExpander implements ProductLabelResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface
     */
    protected $productLabelReader;

    /**
     * @param \Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface $productLabelReader
     */
    public function __construct(ProductLabelReaderInterface $productLabelReader)
    {
        $this->productLabelReader = $productLabelReader;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function addRelationshipsByAbstractSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $abstractSku = $resource->getId();

            $productLabels = $this->productLabelReader->getProductLabelsByAbstractSku(
                $abstractSku,
                $restRequest->getMetadata()->getLocale(),
            );
            foreach ($productLabels as $productLabel) {
                $resource->addRelationship($productLabel);
            }
        }

        return $resources;
    }
}
