<?php

namespace App\Models\Labels\CustomLabels;

use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelContentProperties;
use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelEditorConfig;
use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelSupports;
use App\Models\Labels\CustomLabels\Concerns\RenderCustomLabelContent;
use App\Models\Labels\CustomLabels\Concerns\SeedsCustomLabelFromTemplate;
use App\Models\Labels\Label;
use App\Helpers\Helper;

abstract class CustomTapeLabel extends Label
{
    use RenderCustomLabelContent;
    use HasCustomLabelEditorConfig {
        getContentEditorConfig as getBaseContentEditorConfig;
    }
    use HasCustomLabelSupports;
    use HasCustomLabelContentProperties;
    use SeedsCustomLabelFromTemplate;
    protected array $editorConfig = [];

    protected string $unit = 'mm';

    protected float $width = 50.0;
    protected float $height = 12.0;

    protected float $marginTop = 3.2;
    protected float $marginRight = 3.2;
    protected float $marginBottom = 3.2;
    protected float $marginLeft = 3.2;

    protected int $rotation = 0;
    protected string $orientation = 'L';

    public function getUnit(): string
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

    public function getFieldAlignment(): string
    {
        return $this->fieldAlignment;
    }

    public function getBarcode2DMargin(): float
    {
        return $this->barcode2DMargin;
    }

    public function getBarcode2DPlacement(): ?string
    {
        return $this->barcode2DPlacement;
    }

    public function getLogoMaxHeight(): ?float
    {
        return $this->logoMaxHeight;
    }

    public function getLogoPlacement(): ?string
    {
        return $this->logoPlacement;
    }

    public function getTagPositionMode(): string
    {
        return $this->tagPositionMode;
    }

    public function getTextSizeMod(): float
    {
        return $this->textSizeMod;
    }

    public function getTextAreaOffsetY(): float
    {
        return $this->textAreaOffsetY;
    }

    public function getTextRenderMode(): string
    {
        return $this->textRenderMode;
    }

    public function getBarcode1DVAlign(): string
    {
        return $this->barcode1DVAlign;
    }

    public function getBarcode1DPlacement(): string
    {
        return $this->barcode1DPlacement;
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

    protected function getContentEditorConfig(): array
    {
        return array_merge(
            $this->getBaseContentEditorConfig(),
            [
                'barcode1D_v_align' => $this->getBarcode1DVAlign(),
                'barcode1D_placement' => $this->getBarcode1DPlacement(),

                'barcode2D_placement' => $this->getBarcode2DPlacement(),

                'logo_placement' => $this->getLogoPlacement(),

                'text_size_mod' => $this->getTextSizeMod(),
                'text_area_offset_y' => $this->getTextAreaOffsetY(),
            ]
        );
    }

    public function getEditorConfigSections(): array
    {
        return [
            'unit' => 'mm',
            'printable_area' => $this->getPrintableAreaEditorConfig(),
            'content' => $this->getContentEditorConfig(),
            'supports' => $this->getSupportsEditorConfig(),
        ];
    }

    public function seedFromTemplate($template): static
    {
        $convert = $this->unitConverterFor($template);

        $this->unit = 'mm';

        $this->seedTapeMeasurements($template, $convert);

        $this->seedSupportsFromTemplate($template);
        $this->seedEditorContentFromTemplate($template, $convert);

        $this->rotation = method_exists($template, 'getRotation')
            ? (int)$template->getRotation()
            : $this->rotation;

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
        $this->hydrateSupports($config['supports'] ?? []);
        $this->hydrateContent($config['content'] ?? []);

        $meta = $config['meta'] ?? [];

        $this->rotation = isset($meta['rotation'])
            ? (int)$meta['rotation']
            : $this->rotation;
    }

    public function write($pdf, $record)
    {
        $pa = $this->getPrintableArea();

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
            $this->getTagFont(),
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
            $this->getFieldValueFont(),
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