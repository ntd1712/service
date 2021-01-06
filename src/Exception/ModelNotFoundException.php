<?php

namespace Chaos\Service\Exception;

/**
 * Class ModelNotFoundException.
 *
 * Exception thrown when a resource is not found.
 * In REST context, this exception indicates a status code 404.
 *
 * @author t(-.-t) <ntd1712@mail.com>
 */
class ModelNotFoundException extends ServiceException
{
    protected $message = 'NOT_FOUND';
}
