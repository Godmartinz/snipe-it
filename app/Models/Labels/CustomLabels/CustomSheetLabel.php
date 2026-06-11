<?php

namespace App\Models\Labels\CustomLabels;

use App\Helpers\Helper;
use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelContentProperties;
use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelEditorConfig;
use App\Models\Labels\CustomLabels\Concerns\HasCustomLabelSupports;
use App\Models\Labels\CustomLabels\Concerns\RenderCustomLabelContent;
use App\Models\Labels\CustomLabels\Concerns\SeedsCustomLabelFromTemplate;
use App\Models\Labels\RectangleSheet;

abstract class CustomSheetLabel extends RectangleSheet
{
    use HasCustomLabelContentProperties;
    use RenderCustomLabelContent;
    use HasCustomLabelEditorConfig {
        getContentEditorConfig as getBaseContentEditorConfig;
    }
    use HasCustomLabelSupports;
    use SeedsCustomLabelFromTemplate;
    protected array $editorConfig = [];

    protected string $unit = 'mm';

    /*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/
    protected ?float $pageWidth = 210.0;
    protected ?float $pageHeight = 297.0;
    protected ?float $pageMarginTop = 0.0;
    protected ?float $pageMarginRight = 0.0;
    protected ?float $pageMarginBottom = 0.0;
    protected ?float $pageMarginLeft = 0.0;

    /*
    |--------------------------------------------------------------------------
    | Grid
    |--------------------------------------------------------------------------
    */
    protected int $rows = 9;
    protected int $columns = 3;
    protected float $labelRowSpacing = 0.0;
    protected float $labelColumnSpacing = 0.0;

    /*
    |--------------------------------------------------------------------------
    | Label
    |--------------------------------------------------------------------------
    */
    protected ?float $labelWidth = 50.0;
    protected ?float $labelHeight = 25.0;
    protected float $labelMarginTop = 0.0;
    protected float $labelMarginRight = 0.0;
    protected float $labelMarginBottom = 0.0;
    protected float $labelMarginLeft = 0.0;

    /*
    |--------------------------------------------------------------------------
    | Sheet-only tag positioning
    |--------------------------------------------------------------------------
    */
    protected string $tagHAlign = 'R';
    protected string $tagVAlign = 'B';
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

    public function getLabelBorder()
    {
        return 0;
    }

    public function getTagHAlign(): string
    {
        return $this->tagHAlign;
    }

    public function getTagVAlign(): string
    {
        return $this->tagVAlign;
    }

    public function preparePDF($pdf)
    {
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);
    }

    // The getContenEditorConfig trait shares a lot of common variables, but if sheet specific adjustments are required in the future they can be array_merged here.
    protected function getContentEditorConfig(): array
    {
        return $this->getBaseContentEditorConfig();
    }

    public function seedFromTemplate($template): static
    {
        $convert = $this->unitConverterFor($template);

        $this->unit = 'mm';

        $this->seedSheetMeasurements($template, $convert);
        $this->seedSheetGrid($template);

        $this->seedSupportsFromTemplate($template);
        $this->seedLegacyContentFromTemplate($template, $convert);
        $this->seedEditorContentFromTemplate($template, $convert);

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

        $this->hydrateSheetPage($page);
        $this->hydrateSheetGrid($grid);
        $this->hydrateSheetLabel($label);
        $this->hydrateSupports($supports);
        $this->hydrateContent($content);
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
