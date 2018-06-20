<?php
/**
 * Created by VerveCommerce.
 * User: VerveCommerce
 * Date: 9/14/16
 * Time: 7:28 PM
 */

namespace VerveCommerce\Bigcommerce\Exceptions;

use Exception;

class BigcommerceApiException extends Exception
{

    /**
     * BigcommerceApiException constructor.
     * @param $message
     * @param int code
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}