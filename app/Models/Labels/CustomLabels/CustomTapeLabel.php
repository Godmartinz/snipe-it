<?php

namespace App\Models\Labels\CustomLabels;

use App\Models\Labels\CustomLabels\Concerns\RenderCustomLabelContent;
use App\Models\Labels\Label;
use App\Helpers\Helper;

abstract class CustomTapeLabel extends Label
{
    use RenderCustomLabelContent;
    protected array $editorConfig = [];

    protected string $unit = 'mm';

    protected float $width = 50.0;
    protected float $height = 12.0;

    protected float $marginTop = 3.2;
    protected float $marginRight = 3.2;
    protected float $marginBottom = 3.2;
    protected float $marginLeft = 3.2;

    protected bool $supportAssetTag = true;
    protected bool $support1DBarcode = true;
    protected bool $support2DBarcode = false;
    protected int $supportFields = 1;
    protected bool $supportLogo = false;
    protected bool $supportTitle = false;

    protected float $barcodeSize = 3.2;
    protected float $barcodeMargin = 0.3;
    protected float $textSizeMod = 1.0;
    protected float $tagSize = 5.5;
    protected float $fieldSize = 5.5;
    protected string $tagAlignment = 'L';
    protected string $fieldAlignment = 'R';
    protected float $logoMaxWidth = 12.0;
    protected float $logoMargin = 2.0;
    protected string $logoHAlign = 'L';
    protected string $logoVAlign = 'T';

    protected float $barcode2DSize = 10.0;
    protected string $barcode2DHAlign = 'L';
    protected string $barcode2DVAlign = 'T';
    protected float $tagOffsetX = 0.0;
    protected float $tagOffsetY = 0.0;
    protected string $tagPositionMode = 'free';

    protected float $titleSize = 8.0;
    protected float $titleMargin = 1.0;
    protected float $titleOffsetX = 0.0;

    protected float $labelSize = 5.0;
    protected float $labelMargin = 1.0;
    protected float $fieldMargin = 1.0;
    protected ?float $textAreaWidth = null;
    protected ?float $textAreaHeight = null;

    public function getUnit()
    {
        return $this->unit;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getMarginTop()
    {
        return $this->marginTop;
    }

    public function getMarginRight()
    {
        return $this->marginRight;
    }

    public function getMarginBottom()
    {
        return $this->marginBottom;
    }

    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    public function getSupportAssetTag()
    {
        return $this->supportAssetTag;
    }

    public function getSupport1DBarcode()
    {
        return $this->support1DBarcode;
    }

    public function getSupport2DBarcode()
    {
        return $this->support2DBarcode;
    }

    public function getSupportFields()
    {
        return $this->supportFields;
    }

    public function getSupportLogo()
    {
        return $this->supportLogo;
    }

    public function getSupportTitle()
    {
        return $this->supportTitle;
    }

    public function getBarcodeSize(): float
    {
        return $this->barcodeSize;
    }

    public function getBarcodeMargin(): float
    {
        return $this->barcodeMargin;
    }

    public function getTextSizeMod(): float
    {
        return $this->textSizeMod;
    }

    public function getTagSize(): float
    {
        return $this->tagSize;
    }

    public function getFieldSize(): float
    {
        return $this->fieldSize;
    }

    public function getTagAlignment(): string
    {
        return $this->tagAlignment;
    }

    public function getFieldAlignment(): string
    {
        return $this->fieldAlignment;
    }

    public function getLogoMaxWidth(): float
    {
        return $this->logoMaxWidth;
    }

    public function getLogoMargin(): float
    {
        return $this->logoMargin;
    }

    public function getLogoHAlign(): string
    {
        return $this->logoHAlign;
    }

    public function getLogoVAlign(): string
    {
        return $this->logoVAlign;
    }

    public function get2DBarcodeSize(): float
    {
        return $this->barcode2DSize;
    }

    public function getBarcode2DHAlign(): string
    {
        return $this->barcode2DHAlign;
    }

    public function getBarcode2DVAlign(): string
    {
        return $this->barcode2DVAlign;
    }

    public function getTagOffsetX(): float
    {
        return $this->tagOffsetX;
    }

    public function getTagOffsetY(): float
    {
        return $this->tagOffsetY;
    }

    public function getTagPositionMode(): string
    {
        return $this->tagPositionMode;
    }

    public function getTitleSize(): float
    {
        return $this->titleSize;
    }

    public function getTitleMargin(): float
    {
        return $this->titleMargin;
    }

    public function getTitleOffsetX(): float
    {
        return $this->titleOffsetX;
    }

    public function getLabelSize(): float
    {
        return $this->labelSize;
    }

    public function getLabelMargin(): float
    {
        return $this->labelMargin;
    }

    public function getFieldMargin(): float
    {
        return $this->fieldMargin;
    }

    public function getTextAreaWidth(): ?float
    {
        return $this->textAreaWidth;
    }

    public function getTextAreaHeight(): ?float
    {
        return $this->textAreaHeight;
    }

    public function preparePDF($pdf)
    {
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
    }

    protected function getDimensionsEditorConfig(): array
    {
        return [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'margin_top' => $this->getMarginTop(),
            'margin_right' => $this->getMarginRight(),
            'margin_bottom' => $this->getMarginBottom(),
            'margin_left' => $this->getMarginLeft(),
        ];
    }

    protected function getContentEditorConfig(): array
    {
        return [
            'barcode_size' => $this->getBarcodeSize(),
            'barcode_margin' => $this->getBarcodeMargin(),
            'text_size_mod' => $this->getTextSizeMod(),
            'tag_font_size' => $this->getTagSize(),
            'field_value_font_size' => $this->getFieldSize(),
            'tag_alignment' => $this->getTagAlignment(),
            'field_alignment' => $this->getFieldAlignment(),
        ];
    }

    public function getEditorConfigSections(): array
    {
        return [
            'unit' => 'mm',
            'tape' => $this->getDimensionsEditorConfig(),
            'printable_area' => $this->getPrintableAreaEditorConfig(),
            'content' => $this->getContentEditorConfig(),
            'supports' => $this->getSupportsEditorConfig(),
            'meta' => $this->getMetaEditorConfig(),
        ];
    }

    public function seedFromTemplate($template): static
    {
        $sourceUnit = $template->getUnit();

        $convert = function ($value) use ($sourceUnit) {
            if ($value === null || $value === '') {
                return $value;
            }

            return $sourceUnit === 'in' && is_numeric($value)
                ? (float)$value * 25.4
                : $value;
        };

        $this->unit = 'mm';

        $map = [
            'width' => 'getWidth',
            'height' => 'getHeight',
            'marginTop' => 'getMarginTop',
            'marginRight' => 'getMarginRight',
            'marginBottom' => 'getMarginBottom',
            'marginLeft' => 'getMarginLeft',
        ];

        foreach ($map as $property => $method) {
            if (method_exists($template, $method)) {
                $this->{$property} = (float)$convert($template->{$method}());
            }
        }

        $supportMap = [
            'supportAssetTag' => 'getSupportAssetTag',
            'support1DBarcode' => 'getSupport1DBarcode',
            'support2DBarcode' => 'getSupport2DBarcode',
            'supportFields' => 'getSupportFields',
            'supportLogo' => 'getSupportLogo',
            'supportTitle' => 'getSupportTitle',
        ];

        foreach ($supportMap as $property => $method) {
            if (method_exists($template, $method)) {
                $this->{$property} = $template->{$method}();
            }
        }

        $content = method_exists($template, 'getEditorConfigSections')
            ? ($template->getEditorConfigSections()['content'] ?? [])
            : [];

        $contentMap = [
            'barcode_size' => 'barcodeSize',
            'barcode_margin' => 'barcodeMargin',
            'text_size_mod' => 'textSizeMod',
            'tag_font_size' => 'tagSize',
            'field_value_font_size' => 'fieldSize',
            'tag_alignment' => 'tagAlignment',
            'field_alignment' => 'fieldAlignment',
        ];

        foreach ($contentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = is_numeric($content[$key])
                    ? (float)$convert($content[$key])
                    : (string)$content[$key];
            }
        }

        return $this;
    }

    public function applyEditorConfig(array $config): static
    {
        $this->editorConfig = $config;
        $this->hydrateFromEditorConfig($config);

        return $this;
    }

    protected function hydrateFromEditorConfig(array $config): void
    {
        $tape = $config['tape'] ?? [];
        $content = $config['content'] ?? [];
        $supports = $config['supports'] ?? [];

        $this->width = isset($tape['width']) ? (float)$tape['width'] : $this->width;
        $this->height = isset($tape['height']) ? (float)$tape['height'] : $this->height;

        $this->marginTop = isset($tape['margin_top']) ? (float)$tape['margin_top'] : $this->marginTop;
        $this->marginRight = isset($tape['margin_right']) ? (float)$tape['margin_right'] : $this->marginRight;
        $this->marginBottom = isset($tape['margin_bottom']) ? (float)$tape['margin_bottom'] : $this->marginBottom;
        $this->marginLeft = isset($tape['margin_left']) ? (float)$tape['margin_left'] : $this->marginLeft;

        $this->supportFields = isset($supports['fields']) ? (int)$supports['fields'] : $this->supportFields;
        $this->supportAssetTag = (bool)($supports['asset_tag'] ?? $this->supportAssetTag);
        $this->support1DBarcode = (bool)($supports['barcode_1d'] ?? $this->support1DBarcode);
        $this->support2DBarcode = (bool)($supports['barcode_2d'] ?? $this->support2DBarcode);
        $this->supportLogo = (bool)($supports['logo'] ?? $this->supportLogo);
        $this->supportTitle = (bool)($supports['title'] ?? $this->supportTitle);

        $this->barcodeSize = isset($content['barcode_size']) ? (float)$content['barcode_size'] : $this->barcodeSize;
        $this->barcodeMargin = isset($content['barcode_margin']) ? (float)$content['barcode_margin'] : $this->barcodeMargin;
        $this->textSizeMod = isset($content['text_size_mod']) ? (float)$content['text_size_mod'] : $this->textSizeMod;
        $this->tagSize = isset($content['tag_font_size']) ? (float)$content['tag_font_size'] : $this->tagSize;
        $this->fieldSize = isset($content['field_value_font_size']) ? (float)$content['field_value_font_size'] : $this->fieldSize;
        $this->tagAlignment = isset($content['tag_alignment']) ? (string)$content['tag_alignment'] : $this->tagAlignment;
        $this->fieldAlignment = isset($content['field_alignment']) ? (string)$content['field_alignment'] : $this->fieldAlignment;
    }

    public function write($pdf, $record)
    {
        $pa = $this->getPrintableArea();

        $layout = $this->buildLayout($pdf, $record, $pa);

        $this->render1DBarcode($pdf, $record, $layout);
        $this->renderTapeTag($pdf, $record, $layout);
        $this->renderTapeField($pdf, $record, $layout);
    }

    protected function buildLayout($pdf, $record, $pa): array
    {
        $layout = [
            'printable' => [
                'x1' => $pa->x1,
                'y1' => $pa->y1,
                'x2' => $pa->x2,
                'y2' => $pa->y2,
                'w' => $pa->w,
                'h' => $pa->h,
            ],
            'body' => [
                'x1' => $pa->x1,
                'y1' => $pa->y1,
                'x2' => $pa->x2,
                'y2' => $pa->y2,
                'w' => $pa->w,
                'h' => $pa->h,
            ],
            'barcode1d' => null,
            'barcode2d' => null,
            'logo' => null,
            'tag' => null,
            'field' => null,
            'text' => null,
            'title' => null,
            'fields' => null,
        ];
        $barcodeHeight = 0;
        $barcodeMargin = 0;

        if ($record->has('barcode1d') && $this->getSupport1DBarcode()) {
            $barcodeHeight = min(max(0, $this->getBarcodeSize()), $pa->h);
            $barcodeMargin = max(0, $this->getBarcodeMargin());

            $layout['barcode1d'] = [
                'x' => $pa->x1,
                'y' => $pa->y1,
                'w' => $pa->w,
                'h' => $barcodeHeight,
            ];
        }

        $currentY = $pa->y1 + $barcodeHeight + $barcodeMargin;
        $usableHeight = max(0, $pa->h - $barcodeHeight - $barcodeMargin);
        $fontSize = $usableHeight + $this->getTextSizeMod();

        $tagWidth = $pa->w / 3;
        $fieldWidth = $pa->w - $tagWidth;

        $layout['tag'] = [
            'x' => $pa->x1,
            'y' => $currentY,
            'w' => $tagWidth,
            'h' => $usableHeight,
            'font_size' => $fontSize,
            'align' => $this->getTagAlignment(),
            'spacing' => 0,
        ];

        $layout['field'] = [
            'x' => $pa->x1 + $tagWidth,
            'y' => $currentY,
            'w' => $fieldWidth,
            'h' => $usableHeight,
            'font_size' => $fontSize,
            'align' => $this->getFieldAlignment(),
            'spacing' => 0,
        ];

        return $layout;
    }

    protected function renderTapeTag($pdf, $record, array $layout): void
    {
        if (empty($layout['tag']) || !$record->has('tag') || !$this->getSupportAssetTag()) {
            return;
        }

        static::writeText(
            $pdf,
            $record->get('tag'),
            $layout['tag']['x'],
            $layout['tag']['y'],
            'freemono',
            'B',
            $layout['tag']['font_size'],
            $layout['tag']['align'],
            $layout['tag']['w'],
            $layout['tag']['h'],
            true,
            0,
            $layout['tag']['spacing']
        );
    }

    protected function renderTapeField($pdf, $record, array $layout): void
    {
        if (
            empty($layout['field']) ||
            !$record->has('fields') ||
            $record->get('fields')->count() < 1 ||
            $this->getSupportFields() < 1
        ) {
            return;
        }

        static::writeText(
            $pdf,
            $record->get('fields')->values()->get(0)['value'],
            $layout['field']['x'],
            $layout['field']['y'],
            'freemono',
            'B',
            $layout['field']['font_size'],
            $layout['field']['align'],
            $layout['field']['w'],
            $layout['field']['h'],
            true,
            0,
            $layout['field']['spacing']
        );
    }
}