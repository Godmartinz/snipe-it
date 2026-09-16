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
     * Returns the spacing between columns. Docblock only (no PHP
     * native return type) so subclasses that don't declare return
     * types stay compatible. Widened from int to int|float because
     * some sheet layouts (Hema/_14130046, Hema/_38310012) define
     * fractional spacings (2.0, 3.0, 4.0 mm) that would round
     * incorrectly if coerced to int.
     *
     * @return int|float
     */
    abstract public function getLabelColumnSpacing();

    /**
     * Returns the spacing between rows. See getLabelColumnSpacing for
     * the int|float rationale.
     *
     * @return int|float
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
}
