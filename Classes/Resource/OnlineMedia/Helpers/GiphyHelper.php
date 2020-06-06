<?php

namespace Walther\Giphy\Resource\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOEmbedHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GiphyHelper
 *
 * @package Walther\Giphy\Resource\OnlineMedia\Helpers
 */
class GiphyHelper extends AbstractOEmbedHelper
{
    /**
     * @param string                          $url
     * @param \TYPO3\CMS\Core\Resource\Folder $targetFolder
     *
     * @return \TYPO3\CMS\Core\Resource\File|null
     */
    public function transformUrlToFile($url, Folder $targetFolder)
    {
        // Try to get the YouTube code from given url.
        // These formats are supported with and without http(s)://
        // - media.giphy.com/media/<code>/giphy.gif
        // - media.giphy.com/media/<code>/giphy.mp4
        // - giphy.com/gifs/<code>/html5

        $mediaId = str_replace([
            'http://',
            'https://',
            'media.giphy.com/media/',
            'giphy.com/gifs/',
            '/giphy.gif',
            '/giphy.mp4',
            '/html5'
        ], '', $url);

        if (empty($mediaId)) {
            return null;
        }

        return $this->transformMediaIdToFile($mediaId, $targetFolder, $this->extension);
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\File $file
     *
     * @return string
     */
    public function getPreviewImage(File $file) : string
    {
        $mediaId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'giphy_' . md5($mediaId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $previewImage = GeneralUtility::getUrl(sprintf('https://media.giphy.com/media/%s/giphy.gif', $mediaId));
            if ($previewImage !== false) {
                file_put_contents($temporaryFileName, $previewImage);
                GeneralUtility::fixPermissions($temporaryFileName);
            }
        }

        return $temporaryFileName;
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\File $file
     * @param bool                          $relativeToCurrentScript
     *
     * @return string|NULL
     */
    public function getPublicUrl(File $file, $relativeToCurrentScript = false) : ?string
    {
        $mediaId = $this->getOnlineMediaId($file);
        return sprintf('https://media.giphy.com/media/%s/giphy.gif', $mediaId);
    }

    /**
     * @param string $mediaId
     * @param string $format
     *
     * @return string
     */
    protected function getOEmbedUrl($mediaId, $format = 'json') : string
    {
        return sprintf('https://media.giphy.com/media/%s/giphy.gif', $mediaId);
    }
}
