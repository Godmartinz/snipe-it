<?php

namespace App\Models\Labels\CustomLabels;

use App\Models\Labels\RectangleSheet;

abstract class CustomSheetLabel extends CustomLabel
{
    protected string $unit = 'mm';

    protected ?float $pageWidth = 210.0;
    protected ?float $pageHeight = 297.0;

    protected ?float $pageMarginTop = 0.0;
    protected ?float $pageMarginRight = 0.0;
    protected ?float $pageMarginBottom = 0.0;
    protected ?float $pageMarginLeft = 0.0;

    protected int $rows = 1;
    protected int $columns = 1;

    protected ?float $labelWidth = 50.0;
    protected ?float $labelHeight = 25.0;

    protected float $labelRowSpacing = 0.0;
    protected float $labelColumnSpacing = 0.0;

    protected float $labelMarginTop = 0.0;
    protected float $labelMarginRight = 0.0;
    protected float $labelMarginBottom = 0.0;
    protected float $labelMarginLeft = 0.0;

    protected bool $supportTitle = true;
    protected int $supportFields = 4;
    protected bool $support1DBarcode = true;
    protected bool $support2DBarcode = false;
    protected bool $supportLogo = false;
    protected bool $supportAssetTag = true;

    protected float $barcodeSize = 12.0;
    protected float $barcode2DSize = 12.0;
    protected float $barcodeMargin = 2.0;
    protected float $logoMaxWidth = 12.0;
    protected float $logoMargin = 2.0;

    public function getUnit()
    {
        return $this->unit;
    }

    public function getPageWidth()
    {
        return $this->pageWidth;
    }

    public function getPageHeight()
    {
        return $this->pageHeight;
    }

    public function getPageMarginTop()
    {
        return $this->pageMarginTop;
    }

    public function getPageMarginRight()
    {
        return $this->pageMarginRight;
    }

    public function getPageMarginBottom()
    {
        return $this->pageMarginBottom;
    }

    public function getPageMarginLeft()
    {
        return $this->pageMarginLeft;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getLabelWidth()
    {
        return $this->labelWidth;
    }

    public function getLabelHeight()
    {
        return $this->labelHeight;
    }

    public function getLabelRowSpacing()
    {
        return $this->labelRowSpacing;
    }

    public function getLabelColumnSpacing()
    {
        return $this->labelColumnSpacing;
    }

    public function getLabelMarginTop()
    {
        return $this->labelMarginTop;
    }

    public function getLabelMarginRight()
    {
        return $this->labelMarginRight;
    }

    public function getLabelMarginBottom()
    {
        return $this->labelMarginBottom;
    }

    public function getLabelMarginLeft()
    {
        return $this->labelMarginLeft;
    }

    public function getSupportTitle(): bool
    {
        return $this->supportTitle;
    }

    public function getSupportFields(): int
    {
        return $this->supportFields;
    }

    public function getSupport1DBarcode(): bool
    {
        return $this->support1DBarcode;
    }

    public function getSupport2DBarcode(): bool
    {
        return $this->support2DBarcode;
    }

    public function getSupportLogo(): bool
    {
        return $this->supportLogo;
    }

    public function getSupportAssetTag(): bool
    {
        return $this->supportAssetTag;
    }

    public function getBarcodeSize(): float
    {
        return $this->barcodeSize;
    }

    public function get2DBarcodeSize(): float
    {
        return $this->barcode2DSize;
    }

    public function getBarcodeMargin(): float
    {
        return $this->barcodeMargin;
    }

    public function getLogoMaxWidth(): float
    {
        return $this->logoMaxWidth;
    }

    public function getLogoMargin(): float
    {
        return $this->logoMargin;
    }

    public function getLabelBorder()
    {
        return 0;
    }

    public function preparePDF($pdf)
    {
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
    }

    public function applyEditorConfig(array $config): static
    {
        $this->editorConfig = $config;
        $this->hydrateFromEditorConfig($config);

        return $this;
    }

    protected function hydrateFromEditorConfig(array $config): void
    {
        $page = $config['page'] ?? [];
        $grid = $config['grid'] ?? [];
        $label = $config['label'] ?? [];
        $supports = $config['supports'] ?? [];
        $content = $config['content'] ?? [];

        $this->pageWidth = isset($page['width']) ? (float)$page['width'] : $this->pageWidth;
        $this->pageHeight = isset($page['height']) ? (float)$page['height'] : $this->pageHeight;

        $this->pageMarginTop = isset($page['margin_top']) ? (float)$page['margin_top'] : $this->pageMarginTop;
        $this->pageMarginRight = isset($page['margin_right']) ? (float)$page['margin_right'] : $this->pageMarginRight;
        $this->pageMarginBottom = isset($page['margin_bottom']) ? (float)$page['margin_bottom'] : $this->pageMarginBottom;
        $this->pageMarginLeft = isset($page['margin_left']) ? (float)$page['margin_left'] : $this->pageMarginLeft;

        $this->rows = isset($grid['rows']) ? (int)$grid['rows'] : $this->rows;
        $this->columns = isset($grid['columns']) ? (int)$grid['columns'] : $this->columns;
        $this->labelRowSpacing = isset($grid['row_spacing']) ? (float)$grid['row_spacing'] : $this->labelRowSpacing;
        $this->labelColumnSpacing = isset($grid['column_spacing']) ? (float)$grid['column_spacing'] : $this->labelColumnSpacing;

        $this->labelWidth = isset($label['width']) ? (float)$label['width'] : $this->labelWidth;
        $this->labelHeight = isset($label['height']) ? (float)$label['height'] : $this->labelHeight;

        $this->labelMarginTop = isset($label['padding_top']) ? (float)$label['padding_top'] : $this->labelMarginTop;
        $this->labelMarginRight = isset($label['padding_right']) ? (float)$label['padding_right'] : $this->labelMarginRight;
        $this->labelMarginBottom = isset($label['padding_bottom']) ? (float)$label['padding_bottom'] : $this->labelMarginBottom;
        $this->labelMarginLeft = isset($label['padding_left']) ? (float)$label['padding_left'] : $this->labelMarginLeft;

        $this->supportTitle = isset($supports['title']) ? (bool)$supports['title'] : $this->supportTitle;
        $this->supportFields = isset($supports['fields']) ? (int)$supports['fields'] : $this->supportFields;
        $this->support1DBarcode = isset($supports['barcode_1d']) ? (bool)$supports['barcode_1d'] : $this->support1DBarcode;
        $this->support2DBarcode = isset($supports['barcode_2d']) ? (bool)$supports['barcode_2d'] : $this->support2DBarcode;
        $this->supportLogo = isset($supports['logo']) ? (bool)$supports['logo'] : $this->supportLogo;
        $this->supportAssetTag = isset($supports['asset_tag']) ? (bool)$supports['asset_tag'] : $this->supportAssetTag;

        $this->barcodeSize = isset($content['barcode_size']) ? (float)$content['barcode_size'] : $this->barcodeSize;
        $this->barcodeMargin = isset($content['barcode_margin']) ? (float)$content['barcode_margin'] : $this->barcodeMargin;
        $this->barcode2DSize = isset($content['barcode_2d_size']) ? (float)$content['barcode_2d_size'] : $this->barcode2DSize;
        $this->logoMaxWidth = isset($content['logo_max_width']) ? (float)$content['logo_max_width'] : $this->logoMaxWidth;
        $this->logoMargin = isset($content['logo_margin']) ? (float)$content['logo_margin'] : $this->logoMargin;
    }

    public function write($pdf, $record)
    {
        $pa = $this->getLabelPrintableArea();

        $currentX = $pa->x1;
        $currentY = $pa->y1;
        $usableWidth = $pa->w;
        $usableHeight = $pa->h;

        if ($record->has('barcode1d') && $this->getSupport1DBarcode()) {
            $barcodeSize = $this->getBarcodeSize();
            $barcodeMargin = $this->getBarcodeMargin();

            static::write1DBarcode(
                $pdf,
                $record->get('barcode1d')->content,
                $record->get('barcode1d')->type,
                $pa->x1,
                $pa->y2 - $barcodeSize,
                $usableWidth,
                $barcodeSize
            );

            $usableHeight -= $barcodeSize + $barcodeMargin;
        }
        if ($record->has('logo') && $this->getSupportLogo()) {
            $logoMaxWidth = $this->getLogoMaxWidth();
            $logoMargin = $this->getLogoMargin();

            $logoSize = static::writeImage(
                $pdf,
                $record->get('logo'),
                $pa->x1,
                $pa->y1,
                $logoMaxWidth,
                $usableHeight,
                'L',
                'T',
                300,
                true,
                false,
                0
            );

            $currentX += $logoSize[0] + $logoMargin;
            $usableWidth -= $logoSize[0] + $logoMargin;
        }
        if ($record->has('barcode2d') && $this->getSupport2DBarcode()) {
            $barcodeSize = $this->get2DBarcodeSize();
            $barcodeMargin = $this->getBarcodeMargin();

            static::write2DBarcode(
                $pdf,
                $record->get('barcode2d')->content,
                $record->get('barcode2d')->type,
                $currentX,
                $currentY,
                $barcodeSize,
                $barcodeSize
            );

            $currentX += $barcodeSize + $barcodeMargin;
            $usableWidth -= $barcodeSize + $barcodeMargin;
        }

        if ($record->has('title') && $this->getSupportTitle()) {
            static::writeText(
                $pdf,
                $record->get('title'),
                $currentX,
                $currentY,
                'freesans',
                '',
                8,
                'C',
                $usableWidth,
                8,
                true,
                0
            );

            $currentY += 10;
        }

        if ($record->has('fields') && $this->getSupportFields()) {
            foreach ($record->get('fields') as $field) {
                static::writeText(
                    $pdf,
                    $field['label'] ?? '',
                    $currentX,
                    $currentY,
                    'freesans',
                    '',
                    6,
                    'L',
                    $usableWidth,
                    6,
                    true,
                    0
                );

                $currentY += 7;

                static::writeText(
                    $pdf,
                    $field['value'] ?? '',
                    $currentX,
                    $currentY,
                    'freemono',
                    'B',
                    7,
                    'L',
                    $usableWidth,
                    7,
                    true,
                    0
                );

                $currentY += 9;
            }
        }
    }
}