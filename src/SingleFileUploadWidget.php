<?php

namespace alexeevdv\file;

use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class SingleFileUploadInput
 * @package alexeevdv\file
 */
class SingleFileUploadWidget extends FileInput
{
    /**
     * @var string
     */
    public $containerClass = 'single-file-upload-widget';

    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @inheritdoc
     */
    public function getId($autoGenerate = true)
    {
        if ($this->model) {
            return Html::getInputId($this->model, $this->attribute);
        }
        return parent::getId($autoGenerate);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $value  = $this->model ? $this->model->{$this->attribute} : $this->value;

        $this->pluginOptions = ArrayHelper::merge(
            [
                'layoutTemplates' => 'main2',
                'fileActionSettings' => [
                    'showDrag' => false,
                    'showUpload' => false,
                    'showRemove' => false,
                ],
                'showCaption' => false,
                'showUpload' => false,
                'initialPreview' => [
                    $this->getPublicLink(),
                ],
                'initialPreviewAsData' => true,
                'initialPreviewFileType' => 'other',
                'initialPreviewConfig' => [
                    ['caption' => $value]
                ]
            ],
            $this->pluginOptions
        );

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        SingleFileUploadAsset::register($this->getView());

        $this->getView()->registerJs("
            $('#$this->id').data('fileinput').\$container.addClass('$this->containerClass');
            $('#$this->id').on('fileclear', function(event) {
                $('#$this->id').data('fileinput').\$container.next('input[type=hidden]').val('');
            });
        ");

        $html = parent::run();
        if ($this->model) {
            $html .= Html::activeHiddenInput($this->model, $this->attribute);
        } else {
            $html .= Html::hiddenInput($this->name, $this->value);
        }
        return $html;
    }

    /**
     * @return string|null
     */
    protected function getPublicLink()
    {
        $value = $this->model ? $this->model->{$this->attribute} : $this->value;
        return $value ? $this->getBaseUrl() . '/' . $value : null;
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        if ($this->baseUrl) {
            return rtrim($this->baseUrl, '/');
        }
        $uploadPath = rtrim($this->getSingleFileUploadBehavior()->uploadPath, '/');
        return str_replace('@frontend/web', '', $uploadPath);
    }
    /**
     * @return null|SingleFileUploadBehavior
     */
    protected function getSingleFileUploadBehavior()
    {
        foreach ($this->model->getBehaviors() as $behavior) {
            if ($behavior instanceof SingleFileUploadBehavior) {
                return $behavior;
            }
        }
    }
}
