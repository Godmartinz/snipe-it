<?php

namespace App\Models\Labels\CustomLabels;

use App\Models\Labels\RectangleSheet;

abstract class CustomLabel extends RectangleSheet
{
    protected array $editorConfig = [];

    protected ?float $pageWidth = null;

    protected ?float $pageHeight = null;

    protected ?float $pageMarginTop = null;

    protected ?float $pageMarginRight = null;

    protected ?float $pageMarginBottom = null;

    protected ?float $pageMarginLeft = null;

    protected ?float $labelWidth = null;

    protected ?float $labelHeight = null;

    protected ?float $columnSpacing = null;

    protected ?float $rowSpacing = null;

    protected ?int $gridColumns = null;

    protected ?int $gridRows = null;

    public function applyEditorConfig(array $config): static
    {
        $this->editorConfig = $config;
        $this->hydrateFromEditorConfig($config);

        return $this;
    }

    protected function hydrateFromEditorConfig(array $config): void
    {
        $page = $config['page'] ?? [];
        $label = $config['label'] ?? [];
        $grid = $config['grid'] ?? [];

        $this->pageWidth = $page['width'] ?? $this->pageWidth;
        $this->pageHeight = $page['height'] ?? $this->pageHeight;

        $this->pageMarginTop = $page['margin_top'] ?? $this->pageMarginTop;
        $this->pageMarginRight = $page['margin_right'] ?? $this->pageMarginRight;
        $this->pageMarginBottom = $page['margin_bottom'] ?? $this->pageMarginBottom;
        $this->pageMarginLeft = $page['margin_left'] ?? $this->pageMarginLeft;

        $this->labelWidth = $label['width'] ?? $this->labelWidth;
        $this->labelHeight = $label['height'] ?? $this->labelHeight;

        $this->gridColumns = $grid['columns'] ?? $this->gridColumns;
        $this->gridRows = $grid['rows'] ?? $this->gridRows;
        $this->columnSpacing = $grid['column_spacing'] ?? $this->columnSpacing;
        $this->rowSpacing = $grid['row_spacing'] ?? $this->rowSpacing;
    }
}
