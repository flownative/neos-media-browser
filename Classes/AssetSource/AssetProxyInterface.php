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

use Psr\Http\Message\UriInterface;

interface AssetProxyInterface
{
    public function getIdentifier(): string;

    public function getLabel(): string;

    public function getFilename(): string;

    public function getLastModified(): \DateTimeInterface;

    public function getFileSize(): int;

    public function getMediaType(): string;

    public function getWidthInPixels(): ?int;

    public function getHeightInPixels(): ?int;

    public function getThumbnailUri(): UriInterface;

    public function getPreviewUri(): UriInterface;
}
