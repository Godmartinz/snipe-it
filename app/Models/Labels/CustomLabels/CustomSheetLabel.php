<?php

namespace App\Models\Labels\CustomLabels;

use App\Helpers\Helper;
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

    protected function getContentEditorConfig(): array
    {
        return [
            'barcode_size' => $this->getBarcodeSize(),
            'barcode_margin' => $this->getBarcodeMargin(),
            'barcode_2d_size' => $this->get2DBarcodeSize(),
            'logo_max_width' => $this->getLogoMaxWidth(),
            'logo_margin' => $this->getLogoMargin(),
            'tag_font_size' => $this->getTagSize(),
            'title_font_size' => $this->getTitleSize(),
            'title_margin' => $this->getTitleMargin(),
            'field_label_font_size' => $this->getLabelSize(),
            'field_label_margin' => $this->getLabelMargin(),
            'field_value_font_size' => $this->getFieldSize(),
            'field_value_margin' => $this->getFieldMargin(),
        ];
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

        $this->supportFields = isset($supports['fields']) ? (int)$supports['fields'] : $this->supportFields;
        $this->supportTitle = (bool)($supports['title'] ?? false);
        $this->support1DBarcode = (bool)($supports['barcode_1d'] ?? false);
        $this->support2DBarcode = (bool)($supports['barcode_2d'] ?? false);
        $this->supportLogo = (bool)($supports['logo'] ?? false);
        $this->supportAssetTag = (bool)($supports['asset_tag'] ?? false);

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
        $bottomLimit = $pa->y2;

        if ($record->has('barcode1d') && $this->getSupport1DBarcode()) {
            $barcodeSize = $this->getBarcodeSize();
            $barcodeMargin = $this->getBarcodeMargin();

            $bottomLimit -= $barcodeSize + $barcodeMargin;

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

        $textX = $currentX;
        $textY = $currentY;

        if ($record->has('barcode2d') && $this->getSupport2DBarcode()) {
            $barcodeSize = $this->get2DBarcodeSize();
            $barcodeMargin = $this->getBarcodeMargin();
            $tagSize = $this->getTagSize();

            $barcodeX = $currentX;
            $barcodeY = $currentY;

            static::write2DBarcode(
                $pdf,
                $record->get('barcode2d')->content,
                $record->get('barcode2d')->type,
                $barcodeX,
                $barcodeY,
                $barcodeSize,
                $barcodeSize
            );

            if ($record->has('tag') && $this->getSupportAssetTag()) {
                $tagY = $barcodeY + $barcodeSize + $barcodeMargin;

                static::writeText(
                    $pdf,
                    $record->get('tag'),
                    $barcodeX,
                    $tagY,
                    'freemono',
                    'B',
                    $tagSize,
                    'L',
                    $barcodeSize,
                    $tagSize,
                    true,
                    0,
                    0.3
                );
            }

            $textX = $barcodeX + $barcodeSize + $barcodeMargin;
            $usableWidth = max(0, ($pa->x1 + $pa->w) - $textX);
            $textY = $barcodeY;
        }

        $title = null;
        if ($record->has('title') && $this->getSupportTitle()) {
            $title = $record->get('title');
        }

        $fields = [];
        if ($record->has('fields') && $this->getSupportFields()) {
            $fields = collect($record->get('fields'))
                ->take($this->getSupportFields())
                ->values()
                ->all();
        }

        if ($title !== null || !empty($fields)) {
            $availableHeight = max(0, $bottomLimit - $textY);

            $layout = \App\Helpers\Helper::labelFieldLayoutScaling(
                $pdf,
                $fields,
                $textX,
                $usableWidth,
                $availableHeight,
                $this->getLabelSize(),
                $this->getFieldSize(),
                $this->getFieldMargin(),
                $title,
                $this->getTitleSize(),
                $this->getTitleMargin(),
                $this->getLabelMargin(),
                $this->getFieldMargin()
            );

            if ($layout['hasTitle']) {
                static::writeText(
                    $pdf,
                    $title,
                    $textX,
                    $textY,
                    'freesans',
                    '',
                    $layout['titleSize'],
                    'L',
                    $usableWidth,
                    $layout['titleSize'],
                    true,
                    0
                );

                $textY += $layout['titleAdvance'];
            }

            // Clamp layout widths so value column cannot disappear
            $labelWidth = min($layout['labelWidth'], $usableWidth * 0.45);
            $gap = max(0.8, $this->getFieldMargin() * max($layout['scale'], 0.5));
            $valueX = $textX + $labelWidth + $gap;
            $valueWidth = max(0, ($textX + $usableWidth) - $valueX);

            foreach ($fields as $field) {
                if ($textY + $layout['rowAdvance'] > $bottomLimit) {
                    break;
                }

                $label = $field['label'] ?? '';
                $value = $field['value'] ?? '';

                if (is_string($label) && trim($label) !== '') {
                    $label = rtrim($label, ':') . ':';
                }

                if ($label !== '') {
                    static::writeText(
                        $pdf,
                        $label,
                        $textX,
                        $textY,
                        'freesans',
                        '',
                        $layout['labelSize'],
                        'L',
                        $labelWidth,
                        $layout['labelSize'],
                        true,
                        0
                    );
                }

                if ($valueWidth > 0) {
                    static::writeText(
                        $pdf,
                        $value,
                        $valueX,
                        $textY,
                        'freemono',
                        'B',
                        $layout['fieldSize'],
                        'L',
                        $valueWidth,
                        $layout['fieldSize'],
                        true,
                        0
                    );
                }

                $textY += $layout['rowAdvance'];
            }
        }
    }
}