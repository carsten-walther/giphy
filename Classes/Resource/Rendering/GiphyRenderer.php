<?php

namespace CarstenWalther\Giphy\Resource\Rendering;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class GiphyRenderer
 *
 * @package CarstenWalther\Giphy\Resource\Rendering
 */
class GiphyRenderer implements FileRendererInterface
{
    /**
     * @var bool|OnlineMediaHelperInterface
     */
    protected OnlineMediaHelperInterface|bool $onlineMediaHelper = false;

    /**
     * Returns the priority of the renderer
     * This way it is possible to define/overrule a renderer
     * for a specific file type/context.
     * For example create a video renderer for a certain storage/driver type.
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 100;
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        return ($file->getMimeType() === 'image/giphy' || $file->getExtension() === 'giphy') && $this->getOnlineMediaHelper($file) !== false;
    }

    /**
     * @param FileInterface $file
     *
     * @return bool|OnlineMediaHelperInterface
     */
    protected function getOnlineMediaHelper(FileInterface $file): bool|OnlineMediaHelperInterface
    {
        if (!$this->onlineMediaHelper) {
            $orgFile = $file;
            if ($orgFile instanceof FileReference) {
                $orgFile = $orgFile->getOriginalFile();
            }
            if ($orgFile instanceof File) {
                $this->onlineMediaHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($orgFile);
            } else {
                $this->onlineMediaHelper = false;
            }
        }
        return $this->onlineMediaHelper;
    }

    /**
     * @param FileInterface $file
     * @param int|string $width
     * @param int|string $height
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript
     *
     * @return string
     */
    public function render(
        FileInterface $file,
        $width,
        $height,
        array $options = [],
        bool $usedPathsRelativeToCurrentScript = false
    ): string {
        $options = $this->collectOptions($options, $file);
        $src = $this->createGiphyUrl($options, $file);

        return sprintf(
            '<img src="%s" title="%s" alt="%s" width="%s" height="%s" class="%s"/>',
            htmlspecialchars($src, ENT_QUOTES | ENT_HTML5),
            $options['title'],
            $options['alt'],
            $options['width'] > 0 ? $options['width'] : '',
            $options['height'] > 0 ? $options['height'] : '',
            $options['class']
        );
    }

    /**
     * @param array $options
     * @param FileInterface $file
     *
     * @return array
     */
    protected function collectOptions(array $options, FileInterface $file): array
    {
        return $options;
    }

    /**
     * @param array $options
     * @param FileInterface $file
     *
     * @return string
     */
    protected function createGiphyUrl(array $options, FileInterface $file): string
    {
        $mediaId = $this->getMediaIdFromFile($file);
        return sprintf('https://media.giphy.com/media/%s/giphy.gif', $mediaId);
    }

    /**
     * @param FileInterface $file
     *
     * @return string
     */
    protected function getMediaIdFromFile(FileInterface $file): string
    {
        if ($file instanceof FileReference) {
            $orgFile = $file->getOriginalFile();
        } else {
            $orgFile = $file;
        }
        return $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
    }
}
