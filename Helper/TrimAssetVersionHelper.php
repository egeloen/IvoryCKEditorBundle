<?php

/*
 * This file is part of the Ivory CKEditor package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\CKEditorBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

/**
 * TrimAssetVersionHelper.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 */
class TrimAssetVersionHelper extends Helper
{
    /** @param \Ivory\CKEditorBundle\Helper\AssetVersionTrimer */
    private $trimer;

    /**
     * Constructor.
     *
     * @param \Ivory\CKEditorBundle\Helper\AssetVersionTrimer $trimer
     */
    public function __construct(AssetVersionTrimer $trimer)
    {
        $this->trimer = $trimer;
    }

    /**
     * Calls the AssetVersionTrimer to trim an asset.
     *
     * @see AssetVersionTrimer
     *
     * @param string $asset An asset.
     *
     * @return string A trimed version of the asset.
     */
    public function trim($asset)
    {
        return $this->trimer->trim($asset);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ivory_ck_editor.trim_asset_version';
    }
}
