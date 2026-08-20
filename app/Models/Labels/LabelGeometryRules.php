<?php

namespace App\Models\Labels;

final class LabelGeometryRules
{
    public static function sheet(string $prefix = ''): array
    {
        return [
            "{$prefix}grid.rows" => ['required', 'integer', 'min:1'],
            "{$prefix}grid.columns" => ['required', 'integer', 'min:1'],
            "{$prefix}grid.row_spacing" => ['required', 'numeric', 'min:0'],
            "{$prefix}grid.column_spacing" => ['required', 'numeric', 'min:0'],
            "{$prefix}label.width" => ['required', 'numeric', 'gt:0'],
            "{$prefix}label.height" => ['required', 'numeric', 'gt:0'],
            "{$prefix}page.width" => ['required', 'numeric', 'gt:0'],
            "{$prefix}page.height" => ['required', 'numeric', 'gt:0'],
            // margins / padding / border: 'numeric', 'min:0'
        ];
    }
}