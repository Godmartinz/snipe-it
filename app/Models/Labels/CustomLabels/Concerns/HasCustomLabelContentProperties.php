<?php

namespace App\Models\Labels\CustomLabels\Concerns;

trait HasCustomLabelContentProperties
{
    protected float $barcodeSize = 3.0;
    protected float $barcodeMargin = 0.3;
    protected float $barcode2DSize = 10.0;
    protected string $barcode2DHAlign = 'L';
    protected string $barcode2DVAlign = 'T';
    protected float $tagSize = 5.5;
    protected string $tagAlignment = 'L';
    protected string $tagFont = 'freemono';
    protected float $tagOffsetX = 0.0;
    protected float $tagOffsetY = 0.0;

    protected float $titleSize = 8.0;
    protected float $titleMargin = 1.0;
    protected float $titleOffsetX = 0.0;
    protected string $titleFont = 'freesans';

    protected float $labelSize = 5.0;
    protected float $labelMargin = 1.0;
    protected string $fieldLabelFont = 'freesans';

    protected float $fieldSize = 5.0;
    protected float $fieldMargin = 1.0;
    protected string $fieldValueFont = 'freemono';

    protected float $logoMaxWidth = 12.0;
    protected float $logoMargin = 2.0;
    protected string $logoHAlign = 'L';
    protected string $logoVAlign = 'T';

    protected ?float $textAreaWidth = null;
    protected ?float $textAreaHeight = null;

    public function getBarcodeSize(): float
    {
        return $this->barcodeSize;
    }

    public function getBarcodeMargin(): float
    {
        return $this->barcodeMargin;
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

    public function getTagSize(): float
    {
        return $this->tagSize;
    }

    public function getTagAlignment(): string
    {
        return $this->tagAlignment;
    }

    public function getTagFont(): string
    {
        return $this->tagFont;
    }

    public function getTagOffsetX(): float
    {
        return $this->tagOffsetX;
    }

    public function getTagOffsetY(): float
    {
        return $this->tagOffsetY;
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

    public function getTitleFont(): string
    {
        return $this->titleFont;
    }

    public function getLabelSize(): float
    {
        return $this->labelSize;
    }

    public function getLabelMargin(): float
    {
        return $this->labelMargin;
    }

    public function getFieldSize(): float
    {
        return $this->fieldSize;
    }

    public function getFieldMargin(): float
    {
        return $this->fieldMargin;
    }

    public function getFieldLabelFont(): string
    {
        return $this->fieldLabelFont;
    }

    public function getFieldValueFont(): string
    {
        return $this->fieldValueFont;
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

    public function getTextAreaWidth(): ?float
    {
        return $this->textAreaWidth;
    }

    public function getTextAreaHeight(): ?float
    {
        return $this->textAreaHeight;
    }
}