<?php

namespace App\Models\Labels\CustomLabels;

use App\Helpers\Helper;
use App\Models\Labels\RectangleSheet;

abstract class CustomSheetLabel extends RectangleSheet
{
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
            'barcode_size' => $this->getBarcodeSize(),
            'barcode_margin' => $this->getBarcodeMargin(),
            'barcode2D_h_align' => $this->getBarcode2DHAlign(),
            'barcode2D_v_align' => $this->getBarcode2DVAlign(),
            'tag_alignment' => $this->getTagAlignment(),
            'barcode_2d_size' => $this->get2DBarcodeSize(),
            'logo_max_width' => $this->getLogoMaxWidth(),
            'logo_margin' => $this->getLogoMargin(),
            'logo_h_align' => $this->getLogoHAlign(),
            'logo_v_align' => $this->getLogoVAlign(),
            'tag_font_size' => $this->getTagSize(),
            'tag_offset_x' => $this->getTagOffsetX(),
            'tag_offset_y' => $this->getTagOffsetY(),
            'title_font_size' => $this->getTitleSize(),
            'title_margin' => $this->getTitleMargin(),
            'title_offset_x' => $this->getTitleOffsetX(),
            'field_label_font_size' => $this->getLabelSize(),
            'field_label_margin' => $this->getLabelMargin(),
            'field_value_font_size' => $this->getFieldSize(),
            'field_value_margin' => $this->getFieldMargin(),
            'text_area_width' => $this->getTextAreaWidth(),
            'text_area_height' => $this->getTextAreaHeight(),
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
            'barcode_size' => 'barcodeSize',
            'barcode_margin' => 'barcodeMargin',
            'barcode_2d_size' => 'barcode2DSize',
            'logo_max_width' => 'logoMaxWidth',
            'logo_margin' => 'logoMargin',
            'tag_font_size' => 'tagSize',
            'title_font_size' => 'titleSize',
            'title_margin' => 'titleMargin',
            'field_label_font_size' => 'labelSize',
            'field_label_margin' => 'labelMargin',
            'field_value_font_size' => 'fieldSize',
            'field_value_margin' => 'fieldMargin',
            'text_area_width' => 'textAreaWidth',
            'text_area_height' => 'textAreaHeight',
        ];

        foreach ($contentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = $convert($content[$key]);
            }
        }

        $stringContentMap = [
            'barcode2D_h_align' => 'barcode2DHAlign',
            'barcode2D_v_align' => 'barcode2DVAlign',
            'logo_h_align' => 'logoHAlign',
            'logo_v_align' => 'logoVAlign',
            'tag_alignment' => 'tagAlignment',
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

        $this->pageWidth = isset($page['width']) ? (float) $page['width'] : $this->pageWidth;
        $this->pageHeight = isset($page['height']) ? (float) $page['height'] : $this->pageHeight;

        $this->pageMarginTop = isset($page['margin_top']) ? (float) $page['margin_top'] : $this->pageMarginTop;
        $this->pageMarginRight = isset($page['margin_right']) ? (float) $page['margin_right'] : $this->pageMarginRight;
        $this->pageMarginBottom = isset($page['margin_bottom']) ? (float) $page['margin_bottom'] : $this->pageMarginBottom;
        $this->pageMarginLeft = isset($page['margin_left']) ? (float) $page['margin_left'] : $this->pageMarginLeft;

        $this->rows = isset($grid['rows']) ? (int) $grid['rows'] : $this->rows;
        $this->columns = isset($grid['columns']) ? (int) $grid['columns'] : $this->columns;
        $this->labelRowSpacing = isset($grid['row_spacing']) ? (float) $grid['row_spacing'] : $this->labelRowSpacing;
        $this->labelColumnSpacing = isset($grid['column_spacing']) ? (float) $grid['column_spacing'] : $this->labelColumnSpacing;

        $this->labelWidth = isset($label['width']) ? (float) $label['width'] : $this->labelWidth;
        $this->labelHeight = isset($label['height']) ? (float) $label['height'] : $this->labelHeight;

        $this->labelMarginTop = isset($label['padding_top']) ? (float) $label['padding_top'] : $this->labelMarginTop;
        $this->labelMarginRight = isset($label['padding_right']) ? (float) $label['padding_right'] : $this->labelMarginRight;
        $this->labelMarginBottom = isset($label['padding_bottom']) ? (float) $label['padding_bottom'] : $this->labelMarginBottom;
        $this->labelMarginLeft = isset($label['padding_left']) ? (float) $label['padding_left'] : $this->labelMarginLeft;

        $this->supportFields = isset($supports['fields']) ? (int) $supports['fields'] : $this->supportFields;
        $this->supportTitle = (bool) ($supports['title'] ?? false);
        $this->support1DBarcode = (bool) ($supports['barcode_1d'] ?? false);
        $this->support2DBarcode = (bool) ($supports['barcode_2d'] ?? false);
        $this->supportLogo = (bool) ($supports['logo'] ?? false);
        $this->supportAssetTag = (bool) ($supports['asset_tag'] ?? false);

        $this->barcodeSize = isset($content['barcode_size']) ? (float) $content['barcode_size'] : $this->barcodeSize;
        $this->barcodeMargin = isset($content['barcode_margin']) ? (float) $content['barcode_margin'] : $this->barcodeMargin;
        $this->barcode2DSize = isset($content['barcode_2d_size']) ? (float) $content['barcode_2d_size'] : $this->barcode2DSize;
        $this->logoMaxWidth = isset($content['logo_max_width']) ? (float) $content['logo_max_width'] : $this->logoMaxWidth;
        $this->logoMargin = isset($content['logo_margin']) ? (float) $content['logo_margin'] : $this->logoMargin;
        $this->logoHAlign = isset($content['logo_h_align']) ? (string) $content['logo_h_align'] : $this->logoHAlign;
        $this->logoVAlign = isset($content['logo_v_align']) ? (string) $content['logo_v_align'] : $this->logoVAlign;

        $this->tagSize = isset($content['tag_font_size']) ? (float) $content['tag_font_size'] : $this->tagSize;
        $this->tagOffsetX = isset($content['tag_offset_x']) ? (float) $content['tag_offset_x'] : $this->tagOffsetX;
        $this->tagOffsetY = isset($content['tag_offset_y']) ? (float) $content['tag_offset_y'] : $this->tagOffsetY;
        $this->tagAlignment = isset($content['tag_alignment']) ? (string) $content['tag_alignment'] : $this->tagAlignment;
        $this->titleSize = isset($content['title_font_size']) ? (float) $content['title_font_size'] : $this->titleSize;
        $this->labelSize = isset($content['field_label_font_size']) ? (float) $content['field_label_font_size'] : $this->labelSize;
        $this->fieldSize = isset($content['field_value_font_size']) ? (float) $content['field_value_font_size'] : $this->fieldSize;
        $this->textAreaWidth = isset($content['text_area_width']) && $content['text_area_width'] !== '' ? (float) $content['text_area_width'] : $this->textAreaWidth;
        $this->textAreaHeight = isset($content['text_area_height']) && $content['text_area_height'] !== '' ? (float) $content['text_area_height'] : $this->textAreaHeight;

        $this->titleMargin = isset($content['title_margin']) ? (float) $content['title_margin'] : $this->titleMargin;
        $this->titleOffsetX = isset($content['title_offset_x']) ? (float) $content['title_offset_x'] : $this->titleOffsetX;
        $this->labelMargin = isset($content['field_label_margin']) ? (float) $content['field_label_margin'] : $this->labelMargin;
        $this->fieldMargin = isset($content['field_value_margin']) ? (float) $content['field_value_margin'] : $this->fieldMargin;
    }

    public function write($pdf, $record)
    {
        $pa = $this->getLabelPrintableArea();

        $layout = $this->buildLayout($pdf, $record, $pa);

        $this->render1DBarcode($pdf, $record, $layout);
        $this->renderLogo($pdf, $record, $layout);
        $this->render2DBarcode($pdf, $record, $layout);
        $this->renderTag($pdf, $record, $layout);
        $this->renderTextBlock($pdf, $record, $layout);
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

    protected function boxesOverlap(?array $a, ?array $b): bool
    {
        if (! $a || ! $b) {
            return false;
        }

        return ! (
            $b['x'] >= $a['x'] + $a['w'] ||
            $a['x'] >= $b['x'] + $b['w'] ||
            $b['y'] >= $a['y'] + $a['h'] ||
            $a['y'] >= $b['y'] + $b['h']
        );
    }

    protected function clampBox(array $box, array $container): array
    {
        $box['w'] = min(max(0, $box['w']), $container['w']);
        $box['h'] = min(max(0, $box['h']), $container['h']);

        $box['x'] = max($container['x1'], min($box['x'], $container['x2'] - $box['w']));
        $box['y'] = max($container['y1'], min($box['y'], $container['y2'] - $box['h']));

        return $box;
    }

    protected function anchorBox(array $container, float $w, float $h, string $hAlign, string $vAlign): array
    {
        $w = min($w, $container['w']);
        $h = min($h, $container['h']);

        $x = match (strtoupper($hAlign)) {
            'R' => $container['x2'] - $w,
            'C' => $container['x1'] + (($container['w'] - $w) / 2),
            default => $container['x1'],
        };

        $y = match (strtoupper($vAlign)) {
            'B' => $container['y2'] - $h,
            'C' => $container['y1'] + (($container['h'] - $h) / 2),
            default => $container['y1'],
        };

        return $this->clampBox([
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
        ], $container);
    }

    protected function resolveLogoBox($record, array $body): ?array
    {
        if (! $record->has('logo') || ! $this->getSupportLogo()) {
            return null;
        }

        $width = min($this->getLogoMaxWidth(), $body['w']);

        return $this->anchorBox(
            $body,
            $width,
            $body['h'],
            $this->getLogoHAlign(),
            $this->getLogoVAlign()
        );
    }

    protected function resolve2DBarcodeBox($record, array $body, ?array $logoBox = null): ?array
    {
        if (! $record->has('barcode2d') || ! $this->getSupport2DBarcode()) {
            return null;
        }

        $size = $this->calculate2DBarcodeSize($record, $body);
        $size = min($size, $body['w'], $body['h']);

        $box = $this->anchorBox(
            $body,
            $size,
            $size,
            $this->getBarcode2DHAlign(),
            $this->getBarcode2DVAlign()
        );

        if ($this->boxesOverlap($box, $logoBox)) {
            $altHAlign = strtoupper($this->getBarcode2DHAlign()) === 'R' ? 'L' : 'R';

            $altBox = $this->anchorBox(
                $body,
                $size,
                $size,
                $altHAlign,
                $this->getBarcode2DVAlign()
            );

            if (! $this->boxesOverlap($altBox, $logoBox)) {
                $box = $altBox;
            }
        }

        return $box;
    }

    protected function resolveTagBox($record, array $body, ?array $barcode2dBox = null, ?array $logoBox = null): ?array
    {
        if (! $record->has('tag') || ! $this->getSupportAssetTag()) {
            return null;
        }

        $tagHeight = max(0, $this->getTagSize());

        // If tag and barcode share the same horizontal side, anchor tag to barcode region
        if (
            $barcode2dBox &&
            strtoupper($this->getTagAlignment()) === strtoupper($this->getBarcode2DHAlign())
        ) {
            $box = [
                'x' => $barcode2dBox['x'],
                'y' => $barcode2dBox['y'] + $barcode2dBox['h'],
                'w' => $barcode2dBox['w'],
                'h' => $tagHeight,
            ];

            $box['x'] += $this->getTagOffsetX();
            $box['y'] += $this->getTagOffsetY();

            return $this->clampBox($box, $body);
        }

        $tagWidth = $this->calculateTagWidth($record, $body, $barcode2dBox);
        $tagAlign = strtoupper($this->getTagAlignment());

        if ($tagAlign === 'R') {
            $x = $body['x2'] - $tagWidth;
        } else {
            $x = $body['x1'];
        }

        $box = [
            'x' => $x,
            'y' => $body['y2'] - $tagHeight,
            'w' => $tagWidth,
            'h' => $tagHeight,
        ];

        $box['x'] += $this->getTagOffsetX();
        $box['y'] += $this->getTagOffsetY();

        return $this->clampBox($box, $body);
    }

    protected function render1DBarcode($pdf, $record, array $layout): void
    {
        if (! $layout['barcode1d'] || ! $record->has('barcode1d')) {
            return;
        }

        static::write1DBarcode(
            $pdf,
            $record->get('barcode1d')->content,
            $record->get('barcode1d')->type,
            $layout['barcode1d']['x'],
            $layout['barcode1d']['y'],
            $layout['barcode1d']['w'],
            $layout['barcode1d']['h']
        );
    }

    protected function renderLogo($pdf, $record, array $layout): void
    {
        if (! $layout['logo'] || ! $record->has('logo') || ! $this->getSupportLogo()) {
            return;
        }

        static::writeImage(
            $pdf,
            $record->get('logo'),
            $layout['logo']['x'],
            $layout['logo']['y'],
            $layout['logo']['w'],
            $layout['logo']['h'],
            $this->getLogoHAlign(),
            $this->getLogoVAlign(),
            300,
            true,
            false,
            0
        );
    }

    protected function render2DBarcode($pdf, $record, array $layout): void
    {
        if (! $layout['barcode2d'] || ! $record->has('barcode2d') || ! $this->getSupport2DBarcode()) {
            return;
        }

        static::write2DBarcode(
            $pdf,
            $record->get('barcode2d')->content,
            $record->get('barcode2d')->type,
            $layout['barcode2d']['x'],
            $layout['barcode2d']['y'],
            $layout['barcode2d']['w'],
            $layout['barcode2d']['h']
        );
    }

    protected function renderTag($pdf, $record, array $layout): void
    {
        if (! $layout['tag'] || ! $record->has('tag') || ! $this->getSupportAssetTag()) {
            return;
        }

        static::writeText(
            $pdf,
            $record->get('tag'),
            $layout['tag']['x'],
            $layout['tag']['y'],
            'freemono',
            'B',
            $this->getTagSize(),
            $this->getTagAlignment(),
            $layout['tag']['w'],
            $layout['tag']['h'],
            true,
            0,
            0.3
        );
    }

    protected function renderTextBlock($pdf, $record, array $layout): void
    {
        if ($layout['title']) {
            static::writeText(
                $pdf,
                $record->get('title'),
                $layout['title']['x'],
                $layout['title']['y'],
                'freesans',
                '',
                $layout['title']['font_size'],
                'L',
                $layout['title']['w'],
                $layout['title']['h'],
                true,
                0
            );
        }

        if (! $layout['fields']) {
            return;
        }

        $y = $layout['fields']['start_y'];

        foreach ($layout['fields']['fields'] as $field) {
            if ($y + $layout['fields']['row_advance'] > $layout['fields']['bottom_limit']) {
                break;
            }

            $label = $field['label'] ?? '';
            $value = $field['value'] ?? '';

            if (is_string($label) && trim($label) !== '') {
                $label = rtrim($label, ':').':';
            }

            if ($label !== '') {
                static::writeText(
                    $pdf,
                    $label,
                    $layout['fields']['start_x'],
                    $y,
                    'freesans',
                    '',
                    $layout['fields']['label_size'],
                    'L',
                    $layout['fields']['label_width'],
                    $layout['fields']['label_size'],
                    true,
                    0
                );
            }

            if ($layout['fields']['value_width'] > 0) {
                static::writeText(
                    $pdf,
                    $value,
                    $layout['fields']['value_x'],
                    $y,
                    'freemono',
                    'B',
                    $layout['fields']['field_size'],
                    'L',
                    $layout['fields']['value_width'],
                    $layout['fields']['field_size'],
                    true,
                    0
                );
            }

            $y += $layout['fields']['row_advance'];
        }
    }

    protected function resolveTextBox(array $body, array $boxes): array
    {
        $x1 = $body['x1'];
        $y1 = $body['y1'];
        $x2 = $body['x2'];
        $y2 = $body['y2'];

        $leftEdge = $x1;
        $rightEdge = $x2;
        $topEdge = $y1;
        $bottomEdge = $y2;

        foreach ($boxes as $box) {
            if (! $box) {
                continue;
            }

            $isLeftAnchored = abs($box['x'] - $body['x1']) < 0.01;
            $isRightAnchored = abs(($box['x'] + $box['w']) - $body['x2']) < 0.01;
            $isTopAnchored = abs($box['y'] - $body['y1']) < 0.01;
            $isBottomAnchored = abs(($box['y'] + $box['h']) - $body['y2']) < 0.01;

            if ($isLeftAnchored) {
                $leftEdge = max($leftEdge, $box['x'] + $box['w'] + $this->getLogoMargin());
            }

            if ($isRightAnchored) {
                $rightEdge = min($rightEdge, $box['x'] - $this->getLogoMargin());
            }

            if ($isTopAnchored && $box['w'] > ($body['w'] * 0.6)) {
                $topEdge = max($topEdge, $box['y'] + $box['h'] + $this->getTitleMargin());
            }

            if ($isBottomAnchored && $box['w'] > ($body['w'] * 0.6)) {
                $bottomEdge = min($bottomEdge, $box['y'] - $this->getFieldMargin());
            }
        }

        if ($rightEdge < $leftEdge) {
            $rightEdge = $leftEdge;
        }

        if ($bottomEdge < $topEdge) {
            $bottomEdge = $topEdge;
        }

        return [
            'x1' => $leftEdge,
            'y1' => $topEdge,
            'x2' => $rightEdge,
            'y2' => $bottomEdge,
            'w' => max(0, $rightEdge - $leftEdge),
            'h' => max(0, $bottomEdge - $topEdge),
        ];
    }

    protected function calculate2DBarcodeSize($record, array $container): float
    {
        return min(
            $this->get2DBarcodeSize(),
            $container['w'],
            $container['h']
        );
    }

    protected function calculateTagWidth($record, array $body, ?array $barcode2dBox = null): float
    {
        if ($barcode2dBox && $this->getTagPositionMode() === 'under_barcode') {
            return $barcode2dBox['w'];
        }

        return min($body['w'] * 0.35, $body['w']);
    }
}
