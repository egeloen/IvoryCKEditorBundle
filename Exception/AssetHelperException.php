<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Exception;

/**
 * Asset helper exception.
 *
 * @author Adam Misiorny <adam.misiorny@gmail.com>
 */
class AssetHelperException extends Exception
{
    /**
     * Gets the "MISSING SERVICE" exception.
     *
     * @param array $services List of services
     *
     * @return \Ivory\CKEditorBundle\Exception\AssetHelperException The "MISSING SERVICE" exception.
     */
    public static function missingService(array $services)
    {
        return new static(sprintf('Could not get any service "%s" from service container.', join($services, ', ')));
    }
}
