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
    protected ?string $logoPlacement = null;

    protected float $barcode2DSize = 10.0;
    protected string $barcode2DHAlign = 'L';
    protected string $barcode2DVAlign = 'T';
    protected float $barcode2DMargin = 0.0;
    protected ?float $logoMaxHeight = null;
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
    protected float $textAreaOffsetY = 0.0;

    protected string $textRenderMode = 'inline';
    protected string $barcode1DVAlign = 'T';
    protected string $barcode1DPlacement = 'full_width';
    protected string $barcode2D_h_align = 'L';
    protected ?string $barcode2DPlacement = null;
    protected string $barcode2D_v_align = 'T';

    protected int $rotation = 0;
    protected string $orientation = 'L';

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

    public function getBarcode2DHAlign(): string
    {
        return $this->barcode2DHAlign;
    }

    public function getBarcode2DVAlign(): string
    {
        return $this->barcode2DVAlign;
    }

    public function getBarcode1DPlacement(): string
    {
        return $this->barcode1DPlacement;
    }

    public function getBarcode2DMargin(): float
    {
        return $this->barcode2DMargin;
    }

    public function getLogoMaxHeight(): ?float
    {
        return $this->logoMaxHeight;
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

    public function getLogoPlacement(): ?string
    {
        return $this->logoPlacement;
    }
    public function get2DBarcodeSize(): float
    {
        return $this->barcode2DSize;
    }

    public function getBarcode2DPlacement(): ?string
    {
        return $this->barcode2DPlacement;
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

    public function getTextRenderMode(): string
    {
        return $this->textRenderMode;
    }

    public function getBarcode1DVAlign(): string
    {
        return $this->barcode1DVAlign;
    }

    public function getTextAreaOffsetY(): float
    {
        return $this->textAreaOffsetY;
    }

    public function getRotation()
    {
        return $this->rotation;
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
            'barcode1D_v_align' => $this->getBarcode1DVAlign(),
            'barcode1D_placement' => $this->getBarcode1DPlacement(),
            'barcode_2d_size' => $this->get2DBarcodeSize(),
            'barcode2D_h_align' => $this->getBarcode2DHAlign(),
            'barcode2D_v_align' => $this->getBarcode2DVAlign(),
            'barcode2D_placement' => $this->getBarcode2DPlacement(),
            'text_size_mod' => $this->getTextSizeMod(), // needs to be combined with field size
            'text_area_offset_y' => $this->getTextAreaOffsetY(),
            'tag_font_size' => $this->getTagSize(),
            'tag_offset_x' => $this->getTagOffsetX(),
            'tag_offset_y' => $this->getTagOffsetY(),
            'field_label_font_size' => $this->getLabelSize(),
            'field_value_font_size' => $this->getFieldSize(),
            'field_value_margin' => $this->getFieldMargin(),
            'tag_alignment' => $this->getTagAlignment(),
//            'field_alignment' => $this->getFieldAlignment(),
            'logo_max_width' => $this->getLogoMaxWidth(),
            'logo_margin' => $this->getLogoMargin(),
            'logo_h_align' => $this->getLogoHAlign(),
            'logo_v_align' => $this->getLogoVAlign(),
            'logo_placement' => $this->getLogoPlacement(),
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
            'barcode1D_v_align' => 'barcode1DVAlign',
            'barcode1D_placement' => 'barcode1DPlacement',
            'barcode_2d_size' => 'barcode2DSize',
            'barcode_2d_margin' => 'barcode2DMargin',
            'barcode2D_placement' => 'barcode2DPlacement',
            'logo_max_height' => 'logoMaxHeight',

            'text_size_mod' => 'textSizeMod',
            'text_area_offset_y' => 'textAreaOffsetY',

            'tag_position_mode' => 'tagPositionMode',
            'tag_font_size' => 'tagSize',
            'tag_offset_x' => 'tagOffsetX',
            'tag_offset_y' => 'tagOffsetY',

            'title_font_size' => 'titleSize',
            'title_margin' => 'titleMargin',

            'field_label_font_size' => 'labelSize',
            'field_label_margin' => 'labelMargin',

            'field_value_font_size' => 'fieldSize',
            'field_value_margin' => 'fieldMargin',

            'tag_alignment' => 'tagAlignment',
            'field_alignment' => 'fieldAlignment',

            'text_render_mode' => 'textRenderMode',
        ];

        foreach ($contentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = is_numeric($content[$key])
                    ? (float)$convert($content[$key])
                    : (string)$content[$key];
            }
        }

        $stringContentMap = [
            'barcode2D_h_align' => 'barcode2DHAlign',
            'barcode2D_v_align' => 'barcode2DVAlign',
            'logo_h_align' => 'logoHAlign',
            'logo_v_align' => 'logoVAlign',
            'tag_alignment' => 'tagAlignment',
            'logo_placement' => 'logoPlacement',
        ];

        foreach ($stringContentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = (string)$content[$key];
            }
        }

        foreach ($stringContentMap as $key => $property) {
            if (array_key_exists($key, $content)) {
                $this->{$property} = (string)$content[$key];
            }
        }

        $this->rotation = method_exists($template, 'getRotation')
            ? (int)$template->getRotation()
            : $this->rotation;
        logger()->debug('Template dimensions', [
            'class' => get_class($template),
            'unit' => $template->getUnit(),
            'width' => $template->getWidth(),
            'height' => $template->getHeight(),
        ]);
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
        $meta = $config['meta'] ?? [];

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
        $this->barcode1DVAlign = isset($content['barcode1D_v_align']) ? (string)$content['barcode1D_v_align'] : $this->barcode1DVAlign;
        $this->barcode1DPlacement = isset($content['barcode1D_placement']) ? (string)$content['barcode1D_placement'] : $this->barcode1DPlacement;
        $this->barcode2DHAlign = isset($content['barcode2D_h_align']) ? (string)$content['barcode2D_h_align'] : $this->barcode2DHAlign;
        $this->barcode2DSize = isset($content['barcode_2d_size']) ? (float)$content['barcode_2d_size'] : $this->barcode2DSize;
        $this->barcode2DVAlign = isset($content['barcode2D_v_align']) ? (string)$content['barcode2D_v_align'] : $this->barcode2DVAlign;
        $this->barcode2DMargin = isset($content['barcode_2d_margin']) ? (float)($content['barcode_2d_margin']) : $this->barcode2DMargin;
        $this->barcode2DPlacement = isset($content['barcode2D_placement']) ? (string)$content['barcode2D_placement'] : $this->barcode2DPlacement;

        $this->logoMaxHeight = isset($content['logo_max_height']) ? (float)($content['logo_max_height']) : $this->logoMaxHeight;

        $this->textSizeMod = isset($content['text_size_mod']) ? (float)$content['text_size_mod'] : $this->textSizeMod;
        $this->textAreaOffsetY = isset($content['text_area_offset_y']) ? (float)$content['text_area_offset_y'] : $this->textAreaOffsetY;
        $this->tagSize = isset($content['tag_font_size']) ? (float)$content['tag_font_size'] : $this->tagSize;
        $this->tagOffsetX = isset($content['tag_offset_x']) ? (float)$content['tag_offset_x'] : $this->tagOffsetX;
        $this->tagPositionMode = isset($content['tag_position_mode']) ? (string)$content['tag_position_mode'] : $this->tagPositionMode;
        $this->tagOffsetY = isset($content['tag_offset_y']) ? (float)$content['tag_offset_y'] : $this->tagOffsetY;
        $this->fieldSize = isset($content['field_value_font_size']) ? (float)$content['field_value_font_size'] : $this->fieldSize;
        $this->tagAlignment = isset($content['tag_alignment']) ? (string)$content['tag_alignment'] : $this->tagAlignment;
        $this->fieldAlignment = isset($content['field_alignment']) ? (string)$content['field_alignment'] : $this->fieldAlignment;
        $this->titleSize = isset($content['title_font_size']) ? (float)$content['title_font_size'] : $this->titleSize;
        $this->titleMargin = isset($content['title_margin']) ? (float)$content['title_margin'] : $this->titleMargin;
        $this->labelSize = isset($content['field_label_font_size']) ? (float)$content['field_label_font_size'] : $this->labelSize;
        $this->labelMargin = isset($content['field_label_margin']) ? (float)$content['field_label_margin'] : $this->labelMargin;
        $this->fieldMargin = isset($content['field_value_margin']) ? (float)$content['field_value_margin'] : $this->fieldMargin;
        $this->textRenderMode = isset($content['text_render_mode']) ? (string)$content['text_render_mode'] : $this->textRenderMode;
        $this->logoMargin = isset($content['logo_margin']) ? (float)$content['logo_margin'] : $this->logoMargin;
        $this->logoHAlign = isset($content['logo_h_align']) ? (string)$content['logo_h_align'] : $this->logoHAlign;
        $this->logoVAlign = isset($content['logo_v_align']) ? (string)$content['logo_v_align'] : $this->logoVAlign;
        $this->logoPlacement = isset($content['logo_placement']) ? (string)$content['logo_placement'] : $this->logoPlacement;

        //If the logo and 2D barcode are both present, and one is moved to the other side, they will flip positions.
        if (array_key_exists('barcode2D_h_align', $content)) {
            $this->syncLogoAnd2DBarcodeHAlign('barcode2D_h_align');
        } elseif (array_key_exists('logo_h_align', $content)) {
            $this->syncLogoAnd2DBarcodeHAlign('logo_h_align');
        }

        $this->rotation = isset($meta['rotation']) ? (int)$meta['rotation'] : $this->rotation;
    }

    public function write($pdf, $record)
    {
        $pa = $this->getPrintableArea();

        $layout = $this->buildLayout($pdf, $record, $pa);
        logger()->debug('Tape dimensions', [
            'unit' => $this->getUnit(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
        ]);
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
        | Reserve top strip for 1D barcode
        |--------------------------------------------------------------------------
        */
        $useTextColumnBarcode = $this->getBarcode1DPlacement() === 'text_column';

        if (!$useTextColumnBarcode && $record->has('barcode1d') && $this->getSupport1DBarcode()) {
            $barcodeHeight = min(
                max(0, $this->getBarcodeSize()),
                $layout['body']['h']
            );

            $barcodeMargin = max(0, $this->getBarcodeMargin());

            if (strtoupper($this->getBarcode1DVAlign()) === 'B') {
                $layout['barcode1d'] = [
                    'x' => $layout['body']['x1'],
                    'y' => $layout['body']['y2'] - $barcodeHeight,
                    'w' => $layout['body']['w'],
                    'h' => $barcodeHeight,
                ];

                $layout['body']['y2'] -= ($barcodeHeight + $barcodeMargin);
                $layout['body']['y2'] = max($layout['body']['y1'], $layout['body']['y2']);
            } else {
                $layout['barcode1d'] = [
                    'x' => $layout['body']['x1'],
                    'y' => $layout['body']['y1'],
                    'w' => $layout['body']['w'],
                    'h' => $barcodeHeight,
                ];

                $layout['body']['y1'] += ($barcodeHeight + $barcodeMargin);
                $layout['body']['y1'] = min($layout['body']['y1'], $layout['body']['y2']);
            }

            $layout['body']['h'] = max(
                0,
                $layout['body']['y2'] - $layout['body']['y1']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve positioned elements
        |--------------------------------------------------------------------------
        */
        if ($this->getBarcode2DPlacement() === 'stacked') {
            $barcode2dBox = $this->resolve2DBarcodeBox($record, $layout['body'], null);

            $barcode2dBox['x'] = $layout['body']['x1']
                + (($layout['body']['w'] - $barcode2dBox['w']) / 2);
            
            $layout['barcode2d'] = $barcode2dBox;

            if ($barcode2dBox) {
                $layout['body']['y1'] = $barcode2dBox['y'] + $barcode2dBox['h'] + $this->getBarcode2DMargin();
                $layout['body']['h'] = max(0, $layout['body']['y2'] - $layout['body']['y1']);
            }

            $layout['text'] = $layout['body'];
            $layout['logo'] = null;
            $layout['tag'] = null;
        } elseif ($this->getLogoPlacement() === 'text_column') {
            $barcode2dBox = $this->resolve2DBarcodeBox($record, $layout['body'], null);
            $tagBox = $this->resolveTagBox($record, $layout['body'], $barcode2dBox, null);

            $layout['barcode2d'] = $barcode2dBox;
            $layout['tag'] = $tagBox;

            $layout['text'] = $this->resolveTextBox(
                $layout['body'],
                array_filter([
                    $layout['barcode2d'],
                    $layout['tag'],
                ])
            );

            $logoBox = null;

            if ($record->has('logo') && $this->getSupportLogo()) {
                $logoHeight = $this->getLogoMaxHeight() ?? $this->getLogoMaxWidth();

                $logoBox = $this->anchorBox(
                    $layout['text'],
                    $layout['text']['w'],
                    min($logoHeight, $layout['text']['h']),
                    $this->getLogoHAlign(),
                    $this->getLogoVAlign()
                );
            }
            $layout['logo'] = $logoBox;

            if ($logoBox) {
                $layout['text']['y1'] = $logoBox['y'] + $logoBox['h'] + $this->getLogoMargin();
                $layout['text']['h'] = max(0, $layout['text']['y2'] - $layout['text']['y1']);
            }
        } else {
            $logoBox = $this->resolveLogoBox($record, $layout['body']);
            $barcode2dBox = $this->resolve2DBarcodeBox($record, $layout['body'], $logoBox);
            $tagBox = $this->resolveTagBox($record, $layout['body'], $barcode2dBox, $logoBox);

            $layout['logo'] = $logoBox;
            $layout['barcode2d'] = $barcode2dBox;
            $layout['tag'] = $tagBox;

            $layout['text'] = $this->resolveTextBox(
                $layout['body'],
                array_filter([
                    $layout['logo'],
                    $layout['barcode2d'],
                    $layout['tag'],
                ])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Title + fields
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

        if ($this->getTextAreaOffsetY() !== 0.0) {
            $layout['text']['y2'] += $this->getTextAreaOffsetY();
            $layout['text']['h'] = max(0, $layout['text']['y2'] - $layout['text']['y1']);
        }

        if (
            $useTextColumnBarcode &&
            $record->has('barcode1d') &&
            $this->getSupport1DBarcode()
        ) {
            $barcodeHeight = min(
                max(0, $this->getBarcodeSize()),
                $layout['text']['h']
            );

            $layout['barcode1d'] = [
                'x' => $layout['text']['x1'],
                'y' => $layout['text']['y2'] - $barcodeHeight,
                'w' => $layout['text']['w'],
                'h' => $barcodeHeight,
            ];

            $layout['text']['y2'] -= $barcodeHeight;
            $layout['text']['h'] = max(0, $layout['text']['y2'] - $layout['text']['y1']);
        }
        $textY = $layout['text']['y1'];
        $bottomLimit = $layout['text']['y2'];


        $hasTitle = $title !== null && $title !== '';

        if ($hasTitle) {
            $x = $layout['text']['x1'] + $this->getTitleOffsetX();

            $layout['title'] = [
                'x' => $x,
                'y' => $textY,
                'w' => max(0, $layout['text']['x2'] - $x),
                'h' => $this->getTitleSize(),
                'font_size' => $this->getTitleSize(),
                'advance' => $this->getTitleSize() + $this->getTitleMargin(),
            ];

            $textY += $layout['title']['advance'];
        }

        $labelWidth = min(
            $this->measureTapeLabelWidth($pdf, $fields),
            $layout['text']['w'] * 0.45
        );

        $gap = max(0, $this->getFieldMargin());
        $valueX = $layout['text']['x1'] + $labelWidth + $gap;
        $valueWidth = max(0, $layout['text']['x2'] - $valueX);

        $layout['fields'] = [
            'start_x' => $layout['text']['x1'],
            'start_y' => $textY,
            'bottom_limit' => $bottomLimit,
            'label_width' => $labelWidth,
            'value_x' => $valueX,
            'value_width' => $valueWidth,
            'label_size' => $this->getLabelSize(),
            'field_size' => $this->getFieldSize(),
            'row_advance' => $this->getLabelSize()
                + $this->getLabelMargin()
                + $this->getFieldSize()
                + $this->getFieldMargin(),
            'fields' => $fields,
        ];

        return $layout;
    }

    protected function renderInlineTag($pdf, $record, array $layout): void
    {
        if (empty($layout['tag']) || !$record->has('tag') || !$this->getSupportAssetTag()) {
            return;
        }

        static::writeText(
            $pdf,
            $record->get('tag'),
            $layout['tag']['x'],
            $layout['text']['y1'],
            'freemono',
            'B',
            $layout['text']['h'] + $this->getTextSizeMod(),
            $this->getTagAlignment(),
            $layout['tag']['w'],
            $layout['text']['h'],
            true,
            0,
            0
        );
    }

    protected function renderTag($pdf, $record, array $layout): void
    {
        if ($this->getTextRenderMode() === 'block') {
            $this->renderBlockTag($pdf, $record, $layout);
            return;
        }

        $this->renderInlineTag($pdf, $record, $layout);
    }

    protected function renderTextBlock($pdf, $record, array $layout): void
    {
        if ($this->getTextRenderMode() === 'vertical_stack') {
            $this->renderVerticalStackedTextBlock($pdf, $record, $layout);
            return;
        }

        if ($this->getTextRenderMode() === 'block') {
            $this->renderStackedTextBlock($pdf, $record, $layout);
            return;
        }

        $this->renderInlineText($pdf, $record, $layout);
    }

    protected function renderInlineText($pdf, $record, array $layout): void
    {
        if (empty($layout['fields']) || empty($layout['fields']['fields'])) {
            return;
        }

        $field = collect($layout['fields']['fields'])->first();

        if (!$field) {
            return;
        }

        static::writeText(
            $pdf,
            $field['value'] ?? '',
            $layout['text']['x1'],
            $layout['text']['y1'],
            'freemono',
            'B',
            $layout['text']['h'] + $this->getTextSizeMod(),
            $this->getFieldAlignment(),
            $layout['text']['w'],
            $layout['text']['h'],
            true,
            0,
            0
        );
    }

    protected function measureTapeLabelWidth($pdf, array $fields): float
    {
        $labels = collect($fields)
            ->pluck('label')
            ->filter()
            ->map(fn($label) => rtrim((string)$label, ':') . ':');

        if ($labels->isEmpty()) {
            return 0;
        }

        $prevFamily = $pdf->getFontFamily();
        $prevStyle = $pdf->getFontStyle();
        $prevSizePt = $pdf->getFontSizePt();

        $pdf->SetFont(
            'freesans',
            '',
            \App\Helpers\Helper::convertUnit($this->getLabelSize(), $this->getUnit(), 'pt', true)
        );

        $width = $labels
            ->map(fn($label) => $pdf->GetStringWidth($label))
            ->max();

        $pdf->SetFont($prevFamily, $prevStyle, $prevSizePt);

        return (float)$width;
    }
}