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

/**
 * Asset version trimer.
 *
 * @author Geoffrey Brier <geoffrey.brier@gmail.com>
 */
class AssetVersionTrimer
{
    /**
     * Trims an asset.
     *
     * @param string $asset A string.
     *
     * @return string A trimmed url.
     */
    public function trim($asset)
    {
        $strpos = 'strpos';
        $substr = 'substr';

        if (function_exists('mb_strpos')) {
            $strpos = 'mb_strpos';
        }

        if (function_exists('mb_substr')) {
            $substr = 'mb_substr';
        }

        if (($position = $strpos($asset, '?')) !== false) {
            return $substr($asset, 0, $position);
        }

        return $asset;
    }
}
