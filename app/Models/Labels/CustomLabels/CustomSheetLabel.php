<?php

namespace App\Models\Labels\CustomLabels;

use App\Helpers\Helper;
use App\Models\Labels\CustomLabels\Concerns\RenderCustomLabelContent;
use App\Models\Labels\RectangleSheet;

abstract class CustomSheetLabel extends RectangleSheet
{
    use RenderCustomLabelContent;
    protected array $editorConfig = [];

    protected string $unit = 'mm';

    protected ?float $pageWidth = 210.0;

    protected ?float $pageHeight = 297.0;

    protected ?float $pageMarginTop = 0.0;

    protected ?float $pageMarginRight = 0.0;

    protected ?float $pageMarginBottom = 0.0;

    protected ?float $pageMarginLeft = 0.0;

    protected int $rows = 9;

    protected int $columns = 3;

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

    protected float $barcodeSize = 3;

    protected float $barcode2DSize = 20;

    protected float $barcodeMargin = .025;

    protected float $barcode2Margin = 0.075;

    protected float $logoMaxWidth = 12.0;

    protected float $logoMargin = 2.0;

    protected string $logoHAlign = 'L';

    protected string $logoVAlign = 'T';

    protected float $tagSize = 6.0;

    protected float $titleSize = 8.0;

    protected float $labelSize = 6.0;

    protected float $fieldSize = 7.0;

    protected float $titleMargin = 2.0;

    protected float $titleOffsetX = 0.0;

    protected float $labelMargin = 1.5;

    protected float $fieldMargin = 2.0;

    protected string $tagAlignment = 'L';

    protected string $barcode2DHAlign = 'L';

    protected string $barcode2DVAlign = 'T';

    protected string $tagHAlign = 'R';

    protected string $tagVAlign = 'B';

    protected string $tagPositionMode = 'free';

    protected ?float $textAreaWidth = null;

    protected ?float $textAreaHeight = null;

    protected float $tagOffsetX = 0.0;

    protected float $tagOffsetY = 0.0;
    protected string $tagFont = 'freemono';
    protected string $fieldLabelFont = 'freesans';
    protected string $fieldValueFont = 'freemono';
    protected string $titleFont = 'freesans';

    public function getTagOffsetX(): float
    {
        return $this->tagOffsetX;
    }

    public function getTagOffsetY(): float
    {
        return $this->tagOffsetY;
    }

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

    public function getLogoHAlign(): string
    {
        return $this->logoHAlign;
    }

    public function getLogoVAlign(): string
    {
        return $this->logoVAlign;
    }

    public function getTagSize(): float
    {
        return $this->tagSize;
    }

    public function getTagFont(): string
    {
        return $this->tagFont;
    }

    public function getFieldLabelFont(): string
    {
        return $this->fieldLabelFont;
    }

    public function getFieldValueFont(): string
    {
        return $this->fieldValueFont;
    }

    public function getTitleFont(): string
    {
        return $this->titleFont;
    }

    public function getTagAlignment(): string
    {
        return $this->tagAlignment;
    }

    public function getTitleSize(): float
    {
        return $this->titleSize;
    }

    public function getTitleOffsetX(): float
    {
        return $this->titleOffsetX;
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

    public function getBarcode2DHAlign(): string
    {
        return $this->barcode2DHAlign;
    }

    public function getBarcode2DVAlign(): string
    {
        return $this->barcode2DVAlign;
    }

    public function getTagHAlign(): string
    {
        return $this->tagHAlign;
    }

    public function getTagVAlign(): string
    {
        return $this->tagVAlign;
    }

    public function getTagPositionMode(): string
    {
        return $this->tagPositionMode;
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

    protected function getContentEditorConfig(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Barcode 1D
            |--------------------------------------------------------------------------
            */
            'barcode_size' => $this->getBarcodeSize(),
            'barcode_margin' => $this->getBarcodeMargin(),

            /*
            |--------------------------------------------------------------------------
            | Barcode 2D
            |--------------------------------------------------------------------------
            */
            'barcode_2d_size' => $this->get2DBarcodeSize(),
            'barcode2D_h_align' => $this->getBarcode2DHAlign(),
            'barcode2D_v_align' => $this->getBarcode2DVAlign(),

            /*
            |--------------------------------------------------------------------------
            | Tag
            |--------------------------------------------------------------------------
            */
            'tag_font' => $this->getTagFont(),
            'tag_font_size' => $this->getTagSize(),
            'tag_alignment' => $this->getTagAlignment(),
            'tag_offset_x' => $this->getTagOffsetX(),
            'tag_offset_y' => $this->getTagOffsetY(),

            /*
            |--------------------------------------------------------------------------
            | Title
            |--------------------------------------------------------------------------
            */
            'title_font' => $this->getTitleFont(),
            'title_font_size' => $this->getTitleSize(),
            'title_margin' => $this->getTitleMargin(),
            'title_offset_x' => $this->getTitleOffsetX(),

            /*
            |--------------------------------------------------------------------------
            | Fields
            |--------------------------------------------------------------------------
            */
            'field_label_font' => $this->getFieldLabelFont(),
            'field_label_font_size' => $this->getLabelSize(),
            'field_label_margin' => $this->getLabelMargin(),

            'field_value_font' => $this->getFieldValueFont(),
            'field_value_font_size' => $this->getFieldSize(),
            'field_value_margin' => $this->getFieldMargin(),

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */
            'logo_max_width' => $this->getLogoMaxWidth(),
            'logo_margin' => $this->getLogoMargin(),
            'logo_h_align' => $this->getLogoHAlign(),
            'logo_v_align' => $this->getLogoVAlign(),

            /*
            |--------------------------------------------------------------------------
            | Text Area
            |--------------------------------------------------------------------------
            */
            'text_area_width' => $this->getTextAreaWidth(),
            'text_area_height' => $this->getTextAreaHeight(),
        ];
    }

    public function seedFromTemplate($template): static
    {
        $sourceUnit = $template->getUnit();

        //Convert everything to mm for precision
        $convert = function ($value) use ($sourceUnit) {
            if ($value === null || $value === '') {
                return $value;
            }

            return $sourceUnit === 'in' && is_numeric($value)
                ? (float) $value * 25.4
                : $value;
        };

        $this->unit = 'mm';

        /*
        |--------------------------------------------------------------------------
        | Page / label measurements
        |--------------------------------------------------------------------------
        */
        $measurementMap = [
            'pageWidth' => 'getPageWidth',
            'pageHeight' => 'getPageHeight',
            'pageMarginTop' => 'getPageMarginTop',
            'pageMarginRight' => 'getPageMarginRight',
            'pageMarginBottom' => 'getPageMarginBottom',
            'pageMarginLeft' => 'getPageMarginLeft',

            'labelWidth' => 'getLabelWidth',
            'labelHeight' => 'getLabelHeight',
            'labelRowSpacing' => 'getLabelRowSpacing',
            'labelColumnSpacing' => 'getLabelColumnSpacing',
            'labelMarginTop' => 'getLabelMarginTop',
            'labelMarginRight' => 'getLabelMarginRight',
            'labelMarginBottom' => 'getLabelMarginBottom',
            'labelMarginLeft' => 'getLabelMarginLeft',
        ];

        foreach ($measurementMap as $property => $method) {
            if (method_exists($template, $method)) {
                $this->{$property} = $convert($template->{$method}());
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Grid
        |--------------------------------------------------------------------------
        */
        if (method_exists($template, 'getRows')) {
            $this->rows = (int) $template->getRows();
        }

        if (method_exists($template, 'getColumns')) {
            $this->columns = (int) $template->getColumns();
        }

        /*
        |--------------------------------------------------------------------------
        | Supports
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Legacy getter fallbacks
        |--------------------------------------------------------------------------
        */
        $legacyContentMap = [
            'barcodeSize' => ['getBarcodeSize', 'getBarcode1DSize'],
            'barcode2DSize' => ['get2DBarcodeSize', 'getBarcode2DSize'],
            'barcodeMargin' => ['getBarcodeMargin'],
            'barcode2Margin' => ['getBarcode2DMargin'],
            'logoMaxWidth' => ['getLogoMaxWidth'],
            'logoMargin' => ['getLogoMargin'],
            'tagSize' => ['getTagSize'],
            'titleSize' => ['getTitleSize'],
            'labelSize' => ['getLabelSize'],
            'fieldSize' => ['getFieldSize'],
            'titleMargin' => ['getTitleMargin'],
            'labelMargin' => ['getLabelMargin'],
            'fieldMargin' => ['getFieldMargin'],
        ];

        foreach ($legacyContentMap as $property => $methods) {
            foreach ($methods as $method) {
                if (method_exists($template, $method)) {
                    $this->{$property} = $convert($template->{$method}());
                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Special legacy fallbacks
        |--------------------------------------------------------------------------
        */
        if (method_exists($template, 'getLogoSize') && ! method_exists($template, 'getLogoMaxWidth')) {
            $logoSize = $template->getLogoSize();
            $this->logoMaxWidth = isset($logoSize[0])
                ? $convert($logoSize[0])
                : $this->logoMaxWidth;
        }

        if (method_exists($template, 'getTextSize')) {
            $textSize = $convert($template->getTextSize());

            if (! method_exists($template, 'getTagSize')) {
                $this->tagSize = $textSize;
            }

            if (! method_exists($template, 'getTitleSize')) {
                $this->titleSize = $textSize;
            }

            if (! method_exists($template, 'getLabelSize')) {
                $this->labelSize = $textSize;
            }

            if (! method_exists($template, 'getFieldSize')) {
                $this->fieldSize = $textSize;
            }
        }

        if (method_exists($template, 'getTextMargin')) {
            $textMargin = $convert($template->getTextMargin());

            if (! method_exists($template, 'getTitleMargin')) {
                $this->titleMargin = $textMargin;
            }

            if (! method_exists($template, 'getLabelMargin')) {
                $this->labelMargin = $textMargin;
            }

            if (! method_exists($template, 'getFieldMargin')) {
                $this->fieldMargin = $textMargin;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Editor config overrides
        |--------------------------------------------------------------------------
        |
        | These are applied last so editor-specific values win over legacy getters.
        */
        $content = method_exists($template, 'getEditorConfigSections')
            ? ($template->getEditorConfigSections()['content'] ?? [])
            : [];

        $contentMap = [

            /*
            |--------------------------------------------------------------------------
            | Barcode 1D
            |--------------------------------------------------------------------------
            */
            'barcode_size' => 'barcodeSize',
            'barcode_margin' => 'barcodeMargin',

            /*
            |--------------------------------------------------------------------------
            | Barcode 2D
            |--------------------------------------------------------------------------
            */
            'barcode_2d_size' => 'barcode2DSize',

            /*
            |--------------------------------------------------------------------------
            | Tag
            |--------------------------------------------------------------------------
            */
            'tag_font_size' => 'tagSize',

            /*
            |--------------------------------------------------------------------------
            | Title
            |--------------------------------------------------------------------------
            */
            'title_font_size' => 'titleSize',
            'title_margin' => 'titleMargin',

            /*
            |--------------------------------------------------------------------------
            | Fields
            |--------------------------------------------------------------------------
            */
            'field_label_font_size' => 'labelSize',
            'field_label_margin' => 'labelMargin',

            'field_value_font_size' => 'fieldSize',
            'field_value_margin' => 'fieldMargin',

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */
            'logo_max_width' => 'logoMaxWidth',
            'logo_margin' => 'logoMargin',

            /*
            |--------------------------------------------------------------------------
            | Text Area
            |--------------------------------------------------------------------------
            */
            'text_area_width' => 'textAreaWidth',
            'text_area_height' => 'textAreaHeight',
        ];

        foreach ($contentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = $convert($content[$key]);
            }
        }

        $stringContentMap = [

            /*
            |--------------------------------------------------------------------------
            | Barcode 2D
            |--------------------------------------------------------------------------
            */
            'barcode2D_h_align' => 'barcode2DHAlign',
            'barcode2D_v_align' => 'barcode2DVAlign',

            /*
            |--------------------------------------------------------------------------
            | Tag
            |--------------------------------------------------------------------------
            */
            'tag_alignment' => 'tagAlignment',
            'tag_font' => 'tagFont',

            /*
            |--------------------------------------------------------------------------
            | Title
            |--------------------------------------------------------------------------
            */
            'title_font' => 'titleFont',

            /*
            |--------------------------------------------------------------------------
            | Fields
            |--------------------------------------------------------------------------
            */
            'field_label_font' => 'fieldLabelFont',
            'field_value_font' => 'fieldValueFont',

            /*
            |--------------------------------------------------------------------------
            | Logo
            |--------------------------------------------------------------------------
            */
            'logo_h_align' => 'logoHAlign',
            'logo_v_align' => 'logoVAlign',
        ];

        foreach ($stringContentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = (string) $content[$key];
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
        $page = $config['page'] ?? [];
        $grid = $config['grid'] ?? [];
        $label = $config['label'] ?? [];
        $supports = $config['supports'] ?? [];
        $content = $config['content'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Page
        |--------------------------------------------------------------------------
        */
        $this->pageWidth = isset($page['width']) ? (float) $page['width'] : $this->pageWidth;
        $this->pageHeight = isset($page['height']) ? (float) $page['height'] : $this->pageHeight;
        $this->pageMarginTop = isset($page['margin_top']) ? (float) $page['margin_top'] : $this->pageMarginTop;
        $this->pageMarginRight = isset($page['margin_right']) ? (float) $page['margin_right'] : $this->pageMarginRight;
        $this->pageMarginBottom = isset($page['margin_bottom']) ? (float) $page['margin_bottom'] : $this->pageMarginBottom;
        $this->pageMarginLeft = isset($page['margin_left']) ? (float) $page['margin_left'] : $this->pageMarginLeft;

        /*
        |--------------------------------------------------------------------------
        | Grid
        |--------------------------------------------------------------------------
        */
        $this->rows = isset($grid['rows']) ? (int) $grid['rows'] : $this->rows;
        $this->columns = isset($grid['columns']) ? (int) $grid['columns'] : $this->columns;
        $this->labelRowSpacing = isset($grid['row_spacing']) ? (float) $grid['row_spacing'] : $this->labelRowSpacing;
        $this->labelColumnSpacing = isset($grid['column_spacing']) ? (float) $grid['column_spacing'] : $this->labelColumnSpacing;

        /*
        |--------------------------------------------------------------------------
        | Label
        |--------------------------------------------------------------------------
        */
        $this->labelWidth = isset($label['width']) ? (float) $label['width'] : $this->labelWidth;
        $this->labelHeight = isset($label['height']) ? (float) $label['height'] : $this->labelHeight;
        $this->labelMarginTop = isset($label['padding_top']) ? (float) $label['padding_top'] : $this->labelMarginTop;
        $this->labelMarginRight = isset($label['padding_right']) ? (float) $label['padding_right'] : $this->labelMarginRight;
        $this->labelMarginBottom = isset($label['padding_bottom']) ? (float) $label['padding_bottom'] : $this->labelMarginBottom;
        $this->labelMarginLeft = isset($label['padding_left']) ? (float) $label['padding_left'] : $this->labelMarginLeft;

        /*
        |--------------------------------------------------------------------------
        | Supports
        |--------------------------------------------------------------------------
        */
        $this->supportFields = isset($supports['fields']) ? (int) $supports['fields'] : $this->supportFields;
        $this->supportTitle = (bool)($supports['title'] ?? $this->supportTitle);
        $this->support1DBarcode = (bool)($supports['barcode_1d'] ?? $this->support1DBarcode);
        $this->support2DBarcode = (bool)($supports['barcode_2d'] ?? $this->support2DBarcode);
        $this->supportLogo = (bool)($supports['logo'] ?? $this->supportLogo);
        $this->supportAssetTag = (bool)($supports['asset_tag'] ?? $this->supportAssetTag);

        /*
        |--------------------------------------------------------------------------
        | Barcode 1D
        |--------------------------------------------------------------------------
        */
        $this->barcodeSize = isset($content['barcode_size']) ? (float) $content['barcode_size'] : $this->barcodeSize;
        $this->barcodeMargin = isset($content['barcode_margin']) ? (float) $content['barcode_margin'] : $this->barcodeMargin;

        /*
        |--------------------------------------------------------------------------
        | Barcode 2D
        |--------------------------------------------------------------------------
        */
        $this->barcode2DSize = isset($content['barcode_2d_size']) ? (float) $content['barcode_2d_size'] : $this->barcode2DSize;
        $this->barcode2DHAlign = isset($content['barcode2D_h_align']) ? (string)$content['barcode2D_h_align'] : $this->barcode2DHAlign;
        $this->barcode2DVAlign = isset($content['barcode2D_v_align']) ? (string)$content['barcode2D_v_align'] : $this->barcode2DVAlign;

        /*
        |--------------------------------------------------------------------------
        | Tag
        |--------------------------------------------------------------------------
        */
        $this->tagFont = isset($content['tag_font']) ? (string)$content['tag_font'] : $this->tagFont;
        $this->tagSize = isset($content['tag_font_size']) ? (float) $content['tag_font_size'] : $this->tagSize;
        $this->tagAlignment = isset($content['tag_alignment']) ? (string)$content['tag_alignment'] : $this->tagAlignment;
        $this->tagOffsetX = isset($content['tag_offset_x']) ? (float) $content['tag_offset_x'] : $this->tagOffsetX;
        $this->tagOffsetY = isset($content['tag_offset_y']) ? (float) $content['tag_offset_y'] : $this->tagOffsetY;

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */
        $this->titleFont = isset($content['title_font']) ? (string)$content['title_font'] : $this->titleFont;
        $this->titleSize = isset($content['title_font_size']) ? (float) $content['title_font_size'] : $this->titleSize;
        $this->titleMargin = isset($content['title_margin']) ? (float) $content['title_margin'] : $this->titleMargin;
        $this->titleOffsetX = isset($content['title_offset_x']) ? (float) $content['title_offset_x'] : $this->titleOffsetX;

        /*
        |--------------------------------------------------------------------------
        | Fields
        |--------------------------------------------------------------------------
        */
        $this->fieldLabelFont = isset($content['field_label_font']) ? (string)$content['field_label_font'] : $this->fieldLabelFont;
        $this->labelSize = isset($content['field_label_font_size']) ? (float)$content['field_label_font_size'] : $this->labelSize;
        $this->labelMargin = isset($content['field_label_margin']) ? (float) $content['field_label_margin'] : $this->labelMargin;

        $this->fieldValueFont = isset($content['field_value_font']) ? (string)$content['field_value_font'] : $this->fieldValueFont;
        $this->fieldSize = isset($content['field_value_font_size']) ? (float)$content['field_value_font_size'] : $this->fieldSize;
        $this->fieldMargin = isset($content['field_value_margin']) ? (float) $content['field_value_margin'] : $this->fieldMargin;

        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        */
        $this->logoMaxWidth = isset($content['logo_max_width']) ? (float)$content['logo_max_width'] : $this->logoMaxWidth;
        $this->logoMargin = isset($content['logo_margin']) ? (float)$content['logo_margin'] : $this->logoMargin;
        $this->logoHAlign = isset($content['logo_h_align']) ? (string)$content['logo_h_align'] : $this->logoHAlign;
        $this->logoVAlign = isset($content['logo_v_align']) ? (string)$content['logo_v_align'] : $this->logoVAlign;

        /*
        |--------------------------------------------------------------------------
        | Text Area
        |--------------------------------------------------------------------------
        */
        $this->textAreaWidth = isset($content['text_area_width']) && $content['text_area_width'] !== ''
            ? (float)$content['text_area_width']
            : $this->textAreaWidth;

        $this->textAreaHeight = isset($content['text_area_height']) && $content['text_area_height'] !== ''
            ? (float)$content['text_area_height']
            : $this->textAreaHeight;
        //If the logo and 2D barcode are both present, and one is moved to the other side, they will flip positions.
        if (array_key_exists('barcode2D_h_align', $content)) {
            $this->syncLogoAnd2DBarcodeHAlign('barcode2D_h_align');
        } elseif (array_key_exists('logo_h_align', $content)) {
            $this->syncLogoAnd2DBarcodeHAlign('logo_h_align');
        }
    }

    public function write($pdf, $record)
    {
        $pa = $this->getLabelPrintableArea();

        $layout = $this->buildLayout($pdf, $record, $pa);

        $this->render1DBarcode($pdf, $record, $layout);
        $this->renderLogo($pdf, $record, $layout);
        $this->render2DBarcode($pdf, $record, $layout);
        $this->renderBlockTag($pdf, $record, $layout);
        $this->renderStackedTextBlock($pdf, $record, $layout);
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
            'text' => null,
            'title' => null,
            'fields' => null,
        ];

        /*
        |--------------------------------------------------------------------------
        |Reserve bottom strip for 1D barcode
        |--------------------------------------------------------------------------
        */
        if ($record->has('barcode1d') && $this->getSupport1DBarcode()) {
            $barcodeHeight = max(0, $this->getBarcodeSize());
            $barcodeMargin = max(0, $this->getBarcodeMargin());

            $barcodeHeight = min($barcodeHeight, $layout['body']['h']);

            $layout['barcode1d'] = [
                'x' => $layout['body']['x1'],
                'y' => $layout['body']['y2'] - $barcodeHeight,
                'w' => $layout['body']['w'],
                'h' => $barcodeHeight,
            ];

            $layout['body']['y2'] -= ($barcodeHeight + $barcodeMargin);
            $layout['body']['y2'] = max($layout['body']['y1'], $layout['body']['y2']);
            $layout['body']['h'] = max(0, $layout['body']['y2'] - $layout['body']['y1']);
        }

        /*
        |--------------------------------------------------------------------------
        | add Logo, 2D Barcode, and Tag to the layout
        |--------------------------------------------------------------------------
        */
        $logoBox = $this->resolveLogoBox($record, $layout['body']);
        $barcode2dBox = $this->resolve2DBarcodeBox($record, $layout['body'], $logoBox);
        $tagBox = $this->resolveTagBox($record, $layout['body'], $barcode2dBox, $logoBox);

        $layout['logo'] = $logoBox;
        $layout['barcode2d'] = $barcode2dBox;
        $layout['tag'] = $tagBox;

        /*
        |--------------------------------------------------------------------------
        | Derive remaining text box
        |--------------------------------------------------------------------------
        */
        $layout['text'] = $this->resolveTextBox(
            $layout['body'],
            array_filter([
                $layout['logo'],
                $layout['barcode2d'],
                $layout['tag'],
            ])
        );

        /*
        |--------------------------------------------------------------------------
        | Title + fields inside text box
        |--------------------------------------------------------------------------
        */
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

        $textBox = $layout['text'];

        if ($this->getTextAreaWidth() !== null) {
            $textBox['w'] = min($this->getTextAreaWidth(), $textBox['w']);
            $textBox['x2'] = $textBox['x1'] + $textBox['w'];
        }

        if ($this->getTextAreaHeight() !== null) {
            $textBox['h'] = min($this->getTextAreaHeight(), $textBox['h']);
            $textBox['y2'] = $textBox['y1'] + $textBox['h'];
        }

        $layout['text'] = $textBox;
        $textY = $layout['text']['y1'];
        $bottomLimit = $layout['text']['y2'];
        $availableHeight = max(0, $bottomLimit - $textY);

        $fieldLayout = Helper::labelFieldLayoutScaling(
            $pdf,
            $fields,
            $layout['text']['x1'],
            $layout['text']['w'],
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

        if ($fieldLayout['hasTitle']) {
            $x = $layout['text']['x1'] + $this->getTitleOffsetX();
            $layout['title'] = [
                'x' => $x,
                'y' => $textY,
                'w' => max(0, $layout['text']['x2'] - $x),
                'h' => $fieldLayout['titleSize'],
                'font_size' => $fieldLayout['titleSize'],
                'advance' => $fieldLayout['titleAdvance'],
            ];

            $textY += $fieldLayout['titleAdvance'];
        }

        $labelWidth = min($fieldLayout['labelWidth'], $layout['text']['w'] * 0.45);
        $gap = max(0.8, $this->getFieldMargin() * max($fieldLayout['scale'], 0.5));
        $valueX = $layout['text']['x1'] + $labelWidth + $gap;
        $valueWidth = max(0, $layout['text']['x2'] - $valueX);

        $layout['fields'] = [
            'start_x' => $layout['text']['x1'],
            'start_y' => $textY,
            'bottom_limit' => $bottomLimit,
            'label_width' => $labelWidth,
            'value_x' => $valueX,
            'value_width' => $valueWidth,
            'label_size' => $fieldLayout['labelSize'],
            'field_size' => $fieldLayout['fieldSize'],
            'row_advance' => $fieldLayout['rowAdvance'],
            'fields' => $fields,
        ];

        return $layout;
    }
}
