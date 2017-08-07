<?php

namespace alexeevdv\file;

use yii\web\AssetBundle;

/**
 * Class SingleImageUploadAsset
 * @package alexeevdv\file
 */
class SingleFileUploadAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/alexeevdv/yii2-single-file-upload/src/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/styles.css',
    ];
}
