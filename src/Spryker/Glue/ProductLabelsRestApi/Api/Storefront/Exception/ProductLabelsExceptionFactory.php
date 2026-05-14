<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\ProductLabelsRestApi\Api\Storefront\Exception;

use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductLabelsExceptionFactory
{
    public function createProductLabelIdMissingException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_BAD_REQUEST,
            ProductLabelsRestApiConfig::RESPONSE_CODE_PRODUCT_LABEL_ID_IS_MISSING,
            ProductLabelsRestApiConfig::RESPONSE_DETAIL_PRODUCT_LABEL_ID_IS_MISSING,
        );
    }

    public function createProductLabelNotFoundException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_FOUND,
            ProductLabelsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_LABEL,
            ProductLabelsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_PRODUCT_LABEL,
        );
    }
}
