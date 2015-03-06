<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Templating;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Ivory\CKEditorBundle\Exception\AssetHelperException;

/**
 * CKEditor asset helper.
 *
 * @author Adam Misiorny <adam.misiorny@gmail.com>
 */
class CKEditorAssetHelper
{
    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    private $container;

    /**
     * Creates a CKEditor template asset helper.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container The container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns an public path.
     *
     * @param string $path A path
     *
     * @return string The public path
     */
    public function getUrl($path)
    {
        return $this->getAssetHelper()->getUrl($path);
    }

    /**
     * @return \Symfony\Component\Asset\Packages|\Symfony\Component\Templating\Helper\AssetsHelper
     *
     * @throws \Ivory\CKEditorBundle\Exception\AssetHelperException
     */
    private function getAssetHelper()
    {
        $services = array(
            'assets.packages',
            'templating.helper.assets',
        );

        foreach ($services as $service) {
            if ($this->container->has($service)) {
                return $this->container->get($service);
            }
        }

        throw AssetHelperException::missingService($services);
    }
}
