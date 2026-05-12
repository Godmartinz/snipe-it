<?php

namespace App\Models\Labels\CustomLabels;

use App\Models\Labels\Label;

abstract class CustomTapeLabel extends Label
{
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

        if ($record->has('barcode1d') && $this->getSupport1DBarcode()) {
            static::write1DBarcode(
                $pdf,
                $record->get('barcode1d')->content,
                $record->get('barcode1d')->type,
                $pa->x1,
                $pa->y1,
                $pa->w,
                $this->getBarcodeSize()
            );
        }

        $currentY = $pa->y1 + $this->getBarcodeSize() + $this->getBarcodeMargin();
        $usableHeight = max(0, $pa->h - $this->getBarcodeSize() - $this->getBarcodeMargin());
        $fontSize = $usableHeight + $this->getTextSizeMod();

        $tagWidth = $pa->w / 3;
        $fieldWidth = $pa->w - $tagWidth;

        if ($record->has('tag') && $this->getSupportAssetTag()) {
            static::writeText(
                $pdf,
                $record->get('tag'),
                $pa->x1,
                $currentY,
                'freemono',
                'B',
                $fontSize,
                $this->getTagAlignment(),
                $tagWidth,
                $usableHeight,
                true,
                0,
                0
            );
        }

        if (
            $record->has('fields') &&
            $this->getSupportFields() >= 1 &&
            $record->get('fields')->count() >= 1
        ) {
            static::writeText(
                $pdf,
                $record->get('fields')->values()->get(0)['value'],
                $pa->x1 + $tagWidth,
                $currentY,
                'freemono',
                'B',
                $fontSize,
                $this->getFieldAlignment(),
                $fieldWidth,
                $usableHeight,
                true,
                0,
                0
            );
        }
    }
}