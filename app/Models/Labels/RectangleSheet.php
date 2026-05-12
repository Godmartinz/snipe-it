<?php

namespace App\Models\Labels;

abstract class RectangleSheet extends Sheet
{
    /**
     * Returns the number of columns per sheet
     *
     * @return int
     */
    abstract public function getColumns();

    /**
     * Returns the number of rows per sheet
     *
     * @return int
     */
    abstract public function getRows();

    /**
     * Returns the spacing between columns
     *
     * @return int
     */
    abstract public function getLabelColumnSpacing();

    /**
     * Returns the spacing between rows
     *
     * @return int
     */
    abstract public function getLabelRowSpacing();

    public function getLabelsPerPage()
    {
        return $this->getColumns() * $this->getRows();
    }

    public function getLabelPosition($index)
    {
        $printIndex = $index + $this->getLabelIndexOffset();
        $row = (int) ($printIndex / $this->getColumns());
        $col = $printIndex - ($row * $this->getColumns());
        $x = $this->getPageMarginLeft() + (($this->getLabelWidth() + $this->getLabelColumnSpacing()) * $col);
        $y = $this->getPageMarginTop() + (($this->getLabelHeight() + $this->getLabelRowSpacing()) * $row);

        return [$x, $y];
    }

    public function toEditorConfig(): array
    {
        return array_merge(parent::toEditorConfig(), [
            'grid' => $this->getGridEditorConfig(),
        ]);
    }

    protected function getGridEditorConfig(): array
    {
        return [
            'columns' => $this->getColumns(),
            'rows' => $this->getRows(),
            'column_spacing' => $this->getLabelColumnSpacing(),
            'row_spacing' => $this->getLabelRowSpacing(),
        ];
    }

    public function getEditorConfigSections(): array
    {
        return [
            'unit' => 'mm',
            'page' => $this->getPageEditorConfig(),
            'grid' => $this->getGridEditorConfig(),
            'printable_area' => $this->getPrintableAreaEditorConfig(),
            'label' => $this->getLabelEditorConfig(),
            'content' => $this->getContentEditorConfig(),
            'supports' => $this->getSupportsEditorConfig(),
        ];
    }
}
