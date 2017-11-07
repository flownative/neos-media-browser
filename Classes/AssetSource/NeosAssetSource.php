<?php
namespace Flownative\Media\Browser\AssetSource;

/*
 * This file is part of the Flownative.Media.Browser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 * (c) Robert Lemke, Flownative GmbH - www.flownative.com
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

final class NeosAssetSource implements AssetSourceInterface
{
    /**
     * @var NeosAssetBrowser
     */
    protected $assetBrowser;

    public function getLabel(): string
    {
        return 'Neos';
    }

    /**
     * @return AssetBrowserInterface
     */
    public function getAssetBrowser(): AssetBrowserInterface
    {
        if ($this->assetBrowser === null) {
            $this->assetBrowser = new NeosAssetBrowser();
        }

        return $this->assetBrowser;
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }
}
