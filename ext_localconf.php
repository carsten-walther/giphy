<?php

defined('TYPO3_MODE') or die();

// Add giphy to allowed mediafile extensions
$GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',giphy';
$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'] .= ',giphy';

// Add giphy helper
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['giphy'] = \Walther\Giphy\Resource\OnlineMedia\Helpers\GiphyHelper::class;

// Add giphy as own mimetype
$GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['giphy'] = 'image/giphy';

// Add icon
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon('mimetypes-media-image-giphy', \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, ['source' => 'EXT:giphy/Resources/Public/Icons/mimetypes-media-image-giphy.svg']);
$iconRegistry->registerFileExtension('giphy', 'mimetypes-media-image-giphy');
unset($iconRegistry);

// ImageRenderer/Helper
$rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$rendererRegistry->registerRendererClass(\Walther\Giphy\Resource\Rendering\GiphyRenderer::class);
unset($rendererRegistry);
