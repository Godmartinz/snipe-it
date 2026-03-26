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
    protected float $tagSize = 6.0;
    protected float $titleSize = 8.0;
    protected float $labelSize = 6.0;
    protected float $fieldSize = 7.0;

// Spacing / margins between elements (vertical spacing mostly)
    protected float $titleMargin = 2.0;
    protected float $labelMargin = 1.5;
    protected float $fieldMargin = 2.0;

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

    public function getTagSize(): float
    {
        return $this->tagSize;
    }

    public function getTitleSize(): float
    {
        return $this->titleSize;
    }

    public function getLabelSize(): float
    {
        return $this->labelSize;
    }

    public function getFieldSize(): float
    {
        return $this->fieldSize;
    }

    public function getTitleMargin(): float
    {
        return $this->titleMargin;
    }

    public function getLabelMargin(): float
    {
        return $this->labelMargin;
    }

    public function getFieldMargin(): float
    {
        return $this->fieldMargin;
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

    public function seedFromTemplate($template): static
    {
        $this->unit = $template->getUnit();

        $this->pageWidth = $template->getPageWidth();
        $this->pageHeight = $template->getPageHeight();

        $this->pageMarginTop = $template->getPageMarginTop();
        $this->pageMarginRight = $template->getPageMarginRight();
        $this->pageMarginBottom = $template->getPageMarginBottom();
        $this->pageMarginLeft = $template->getPageMarginLeft();

        $this->rows = $template->getRows();
        $this->columns = $template->getColumns();

        $this->labelWidth = $template->getLabelWidth();
        $this->labelHeight = $template->getLabelHeight();

        $this->labelRowSpacing = $template->getLabelRowSpacing();
        $this->labelColumnSpacing = $template->getLabelColumnSpacing();

        $this->labelMarginTop = $template->getLabelMarginTop();
        $this->labelMarginRight = $template->getLabelMarginRight();
        $this->labelMarginBottom = $template->getLabelMarginBottom();
        $this->labelMarginLeft = $template->getLabelMarginLeft();

        $this->supportAssetTag = $template->getSupportAssetTag();
        $this->support1DBarcode = $template->getSupport1DBarcode();
        $this->support2DBarcode = $template->getSupport2DBarcode();
        $this->supportFields = $template->getSupportFields();
        $this->supportLogo = $template->getSupportLogo();
        $this->supportTitle = $template->getSupportTitle();

        if (method_exists($template, 'getBarcodeSize')) {
            $this->barcodeSize = $template->getBarcodeSize();
        }

        if (method_exists($template, 'getBarcodeMargin')) {
            $this->barcodeMargin = $template->getBarcodeMargin();
        }

        if (method_exists($template, 'getLogoMaxWidth')) {
            $this->logoMaxWidth = $template->getLogoMaxWidth();
        }

        if (method_exists($template, 'getLogoMargin')) {
            $this->logoMargin = $template->getLogoMargin();
        }

        if (method_exists($template, 'getTagSize')) {
            $this->tagSize = $template->getTagSize();
        }

        if (method_exists($template, 'getTitleSize')) {
            $this->titleSize = $template->getTitleSize();
        }

        if (method_exists($template, 'getTitleMargin')) {
            $this->titleMargin = $template->getTitleMargin();
        }

        if (method_exists($template, 'getLabelSize')) {
            $this->labelSize = $template->getLabelSize();
        }

        if (method_exists($template, 'getLabelMargin')) {
            $this->labelMargin = $template->getLabelMargin();
        }

        if (method_exists($template, 'getFieldSize')) {
            $this->fieldSize = $template->getFieldSize();
        }

        if (method_exists($template, 'getFieldMargin')) {
            $this->fieldMargin = $template->getFieldMargin();
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

        $this->tagSize = isset($content['tag_font_size']) ? (float)$content['tag_font_size'] : $this->tagSize;
        $this->titleSize = isset($content['title_font_size']) ? (float)$content['title_font_size'] : $this->titleSize;
        $this->labelSize = isset($content['field_label_font_size']) ? (float)$content['field_label_font_size'] : $this->labelSize;
        $this->fieldSize = isset($content['field_value_font_size']) ? (float)$content['field_value_font_size'] : $this->fieldSize;

        $this->titleMargin = isset($content['title_margin']) ? (float)$content['title_margin'] : $this->titleMargin;
        $this->labelMargin = isset($content['field_label_margin']) ? (float)$content['field_label_margin'] : $this->labelMargin;
        $this->fieldMargin = isset($content['field_value_margin']) ? (float)$content['field_value_margin'] : $this->fieldMargin;
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
                $this->getTitleSize(),
                'L',
                $usableWidth,
                8,
                true,
                0
            );

            $currentY += $this->getTitleSize() + $this->getTitleMargin();
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
                    $this->getLabelSize(),
                    'L',
                    $usableWidth,
                    $this->getLabelSize(),
                    true,
                    0
                );

                $currentY += $this->getLabelSize() + $this->getLabelMargin();

                static::writeText(
                    $pdf,
                    $field['value'] ?? '',
                    $currentX,
                    $currentY,
                    'freemono',
                    'B',
                    $this->getFieldSize(),
                    'L',
                    $usableWidth,
                    $this->getFieldSize(),
                    true,
                    0
                );

                $currentY += $this->getFieldSize() + $this->getFieldMargin();
            }
        }

        if ($record->has('tag') && $this->getSupportAssetTag()) {
            static::writeText(
                $pdf,
                $record->get('tag'),
                $currentX,
                $pa->y2 - $this->getBarcodeSize() - $this->getBarcodeMargin() - $this->getTagSize(),
                'freemono',
                'B',
                $this->getTagSize(),
                'R',
                $usableWidth,
                $this->getTagSize(),
                true,
                0,
                0.3
            );
        }
    }
}