<?php

namespace App\Models\Labels\CustomLabels;

class PreviewTapeLabel extends CustomTapeLabel
{
    protected float $width = 50.0;
    protected float $height = 24.0;
    protected ?float $gap = 0;
    protected bool $supportAssetTag = true;
    protected bool $support1DBarcode = true;
    protected bool $support2DBarcode = true;
    protected int $supportFields = 5;
    protected bool $supportLogo = true;
    protected bool $supportTitle = true;

    public function __construct(
        float $width = 50.0,
        float $height = 24.0,
        float $gap = 0,
    )
    {
        $this->width = $width;
        $this->height = $height;
        $this->gap = $gap;
    }

}