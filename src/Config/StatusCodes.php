<?php declare(strict_types=1);

namespace KikCMS\Config;

/**
 * Custom http status codes
 */
class StatusCodes
{
    /** The response is valid, but the form input was not */
    const FORM_INVALID         = 250;
    const FORM_INVALID_MESSAGE = 'Invalid form input';

    /** Session expired */
    const SESSION_EXPIRED         = 440;
    const SESSION_EXPIRED_MESSAGE = 'Session expired';

    /** Service Unavailable */
    const SERVICE_UNAVAILABLE         = 503;
    const SERVICE_UNAVAILABLE_MESSAGE = 'Your request could not be completed due to insufficient resources. Please try again later.';
}