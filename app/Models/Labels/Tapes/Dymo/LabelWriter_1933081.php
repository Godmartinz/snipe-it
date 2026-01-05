<?php

namespace App\Models\Labels\Tapes\Dymo;


class LabelWriter_1933081 extends LabelWriter
{
    private const BARCODE_MARGIN =   1.80;
    private const TAG_SIZE       =   2.80;
    private const TITLE_SIZE     =   2.80;
    private const TITLE_MARGIN   =   0.50;
    private const LABEL_SIZE     =   2.80;
    private const LABEL_MARGIN   = - 0.35;
    private const FIELD_SIZE     =   2.80;
    private const FIELD_MARGIN   =   0.15;

    public function getUnit()
    {
        return 'mm'; 
    }
    public function getWidth()
    {
        return 89; 
    }
    public function getHeight()
    {
        return 25; 
    }
    public function getSupportAssetTag()
    {
        return true; 
    }
    public function getSupport1DBarcode()
    {
        return true; 
    }
    public function getSupport2DBarcode()
    {
        return true; 
    }
    public function getSupportFields()
    {
        return 5; 
    }
    public function getSupportLogo()
    {
        return false; 
    }
    public function getSupportTitle()
    {
        return true; 
    }

    public function preparePDF($pdf)
    {
    }

    public function write($pdf, $record)
    {
        $pa = $this->getPrintableArea();

        $currentX = $pa->x1;
        $currentY = $pa->y1;
        $usableWidth = $pa->w;

        $barcodeSize = $pa->h - self::TAG_SIZE;

        if ($record->has('barcode2d')) {
            static::writeText(
                $pdf, $record->get('tag'),
                $pa->x1, $pa->y2 - self::TAG_SIZE,
                'freesans', 'b', self::TAG_SIZE, 'C',
                $barcodeSize, self::TAG_SIZE, true, 0
            );
            static::write2DBarcode(
                $pdf, $record->get('barcode2d')->content, $record->get('barcode2d')->type,
                $currentX, $currentY,
                $barcodeSize, $barcodeSize
            );
            $currentX += $barcodeSize + self::BARCODE_MARGIN;
            $usableWidth -= $barcodeSize + self::BARCODE_MARGIN;
        } 

        if ($record->has('title')) {
            static::writeText(
                $pdf, $record->get('title'),
                $currentX, $currentY,
                'freesans', 'b', self::TITLE_SIZE, 'L',
                $usableWidth, self::TITLE_SIZE, true, 0
            );
            $currentY += self::TITLE_SIZE + self::TITLE_MARGIN;
        }

        $fields = $record->get('fields');
        // Below rescales the size of the field box to fit, it feels like it could/should be abstracted one class above
        // to be usable on other labels but im unsure of how to implement that, since it uses a lot of private
        // constants.

        // Figure out how tall the label fields wants to be
        $fieldCount = count($fields);
        $perFieldHeight = (self::LABEL_SIZE + self::LABEL_MARGIN)
            + (self::FIELD_SIZE + self::FIELD_MARGIN);
        $usableHeight = $pa->h
            - self::TAG_SIZE           // bottom tag text
            - self::BARCODE_MARGIN;    // gap between fields and 1D

        $baseHeight = $fieldCount * $perFieldHeight;
        // If it doesn't fit in the available height, scale everything down
        $scale = 1.0;
        if ($baseHeight > $usableHeight && $baseHeight > 0) {
            $scale = $usableHeight / $baseHeight;
        }

        $labelSize   = self::LABEL_SIZE   * $scale;
        $labelMargin = self::LABEL_MARGIN * $scale;
        $fieldSize   = self::FIELD_SIZE   * $scale;
        $fieldMargin = self::FIELD_MARGIN * $scale;

        $pdf->SetFont('freesans', '', $labelSize);

        $maxLabelWidth = 0;
        foreach ($fields as $field) {
            $labelText = rtrim($field['label'], ':') . ':';
            $w = $pdf->GetStringWidth($labelText);
            if ($w > $maxLabelWidth) {
                $maxLabelWidth = $w;
            }
        }
        $labelPadding = 1.5 * $scale;          // breathing room after label text
        $gap          = 1.5 * $scale;          // gap between label column and value column
        $labelWidth   = $maxLabelWidth + $labelPadding;
        foreach ($fields as $field) {
            $labelText  = rtrim($field['label'], ':') . ':';
            $valueX     = $currentX + $labelWidth + $gap;
            $valueWidth = $usableWidth - $labelWidth - $gap;

            static::writeText(
                $pdf,$labelText,
                $currentX, $currentY,
                'freesans', '', $labelSize, 'L',
                $labelWidth, $labelSize, true, 0
            );
            static::writeText(
                $pdf, $field['value'],
                $valueX, $currentY,
                'freemono', 'B', $fieldSize, 'L',
                $usableWidth, $fieldSize, true, 0, 0.01
            );
            $currentY += $fieldSize + $fieldMargin;
        }

        if ($record->has('barcode1d')) {
            static::write1DBarcode(
                $pdf, $record->get('barcode1d')->content, $record->get('barcode1d')->type,
                $currentX, $barcodeSize + self::BARCODE_MARGIN, $usableWidth - self::TAG_SIZE, self::TAG_SIZE
            );
        }
    }

}
