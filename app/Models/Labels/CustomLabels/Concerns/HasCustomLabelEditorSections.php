<?php

namespace App\Models\Labels\CustomLabels\Concerns;


trait HasCustomLabelEditorSections
{
    public function getEditorSections(): array
    {
        return array_replace_recursive(
            $this->baseEditorSections(),
            $this->layoutEditorSections()
        );
    }

    protected function baseEditorSections(): array
    {
        return [
            'supports' => [
                'label' => 'admin/labels/general.sections.supports',
                'column_span' => 2,
                'display' => 'inline',
                'fields' => [
                    'fields' => ['type' => 'number'],
                    'asset_tag' => ['type' => 'checkbox'],
                    'barcode_1d' => ['type' => 'checkbox'],
                    'barcode_2d' => ['type' => 'checkbox'],
                    'logo' => ['type' => 'checkbox'],
                    'title' => ['type' => 'checkbox'],
                ],
            ],
            'content' => [
                'label' => 'admin/labels/general.sections.content',
                'column_span' => 2,
                'groups' => [
                    'barcode_1d' => [
                        'label' => 'admin/labels/general.groups.barcode_1d',
                        'toggle' => 'supports.barcode_1d',
                        'fields' => [
                            'barcode_size' => ['type' => 'number'],
                            'barcode_margin' => ['type' => 'number'],
                            'barcode1D_v_align' => [
                                'type' => 'select',
                                'options' => [
                                    'T' => 'admin/labels/general.options.top',
                                    'M' => 'admin/labels/general.options.middle',
                                    'B' => 'admin/labels/general.options.bottom',
                                ],
                            ],
                        ],
                    ],

                    'barcode_2d' => [
                        'label' => 'admin/labels/general.groups.barcode_2d',
                        'toggle' => 'supports.barcode_2d',
                        'fields' => [
                            'barcode_2d_size' => ['type' => 'number'],
                            'barcode2D_h_align' => [
                                'type' => 'select',
                                'options' => [
                                    'L' => 'admin/labels/general.options.left',
                                    'C' => 'admin/labels/general.options.center',
                                    'R' => 'admin/labels/general.options.right',
                                ],
                            ],
                            'barcode2D_v_align' => [
                                'type' => 'select',
                                'options' => [
                                    'T' => 'admin/labels/general.options.top',
                                    'M' => 'admin/labels/general.options.middle',
                                    'B' => 'admin/labels/general.options.bottom',
                                ],
                            ],
                        ],
                    ],

                    'tag' => [
                        'label' => 'admin/labels/general.groups.tag',
                        'toggle' => 'supports.asset_tag',
                        'fields' => [
                            'tag_font' => ['type' => 'text'],
                            'tag_font_size' => ['type' => 'number'],
                            'tag_alignment' => [
                                'type' => 'select',
                                'options' => [
                                    'L' => 'admin/labels/general.options.left',
                                    'C' => 'admin/labels/general.options.center',
                                    'R' => 'admin/labels/general.options.right',
                                ],
                            ],
                            'tag_offset_x' => ['type' => 'number'],
                            'tag_offset_y' => ['type' => 'number'],
                        ],
                    ],

                    'title' => [
                        'label' => 'admin/labels/general.groups.title',
                        'toggle' => 'supports.title',
                        'fields' => [
                            'title_font' => ['type' => 'text'],
                            'title_font_size' => ['type' => 'number'],
                            'title_margin' => ['type' => 'number'],
                            'title_offset_x' => ['type' => 'number'],
                            'title_position' => [
                                'type' => 'select',
                                'options' => [
                                    'inline' => 'admin/labels/general.options.inline',
                                    'top' => 'admin/labels/general.options.top',

                                ],
                            ],
                        ],
                    ],

                    'field_labels' => [
                        'label' => 'admin/labels/general.groups.field_labels',
                        'toggle' => 'supports.fields',
                        'fields' => [
                            'field_label_font' => ['type' => 'text'],
                            'field_label_font_size' => ['type' => 'number'],
                            'field_label_margin' => ['type' => 'number'],
                        ],
                    ],

                    'field_values' => [
                        'label' => 'admin/labels/general.groups.field_values',
                        'toggle' => 'supports.fields',
                        'fields' => [
                            'field_value_font' => ['type' => 'text'],
                            'field_value_font_size' => ['type' => 'number'],
                            'field_value_margin' => ['type' => 'number'],
                        ],
                    ],

                    'logo' => [
                        'label' => 'admin/labels/general.groups.logo',
                        'toggle' => 'supports.logo',
                        'fields' => [
                            'logo_max_width' => ['type' => 'number'],
                            'logo_margin' => ['type' => 'number'],
                            'logo_h_align' => [
                                'type' => 'select',
                                'options' => [
                                    'L' => 'admin/labels/general.options.left',
                                    'C' => 'admin/labels/general.options.center',
                                    'R' => 'admin/labels/general.options.right',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function layoutEditorSections(): array
    {
        if ($this->isTapeLabel()) {
            return $this->tapeLayoutEditorSections();
        }

        return $this->sheetLayoutEditorSections();
    }

    protected function isTapeLabel(): bool
    {
        return str_contains(static::class, 'Tape')
            || method_exists($this, 'getTapeWidth')
            || method_exists($this, 'getTapeHeight');
    }

    protected function sheetLayoutEditorSections(): array
    {
        return [
            'layout' => [
                'label' => 'admin/labels/general.sections.layout',
                'column_span' => 2,
                'groups' => [
                    'page' => [
                        'label' => 'admin/labels/general.sections.page',
                        'section_key' => 'page',
                        'fields' => [
                            'width' => ['type' => 'number'],
                            'height' => ['type' => 'number'],
                            'margin_top' => ['type' => 'number'],
                            'margin_right' => ['type' => 'number'],
                            'margin_bottom' => ['type' => 'number'],
                            'margin_left' => ['type' => 'number'],
                        ],
                    ],

                    'grid' => [
                        'label' => 'admin/labels/general.sections.grid',
                        'section_key' => 'grid',
                        'fields' => [
                            'columns' => ['type' => 'number'],
                            'rows' => ['type' => 'number'],
                            'column_spacing' => ['type' => 'number'],
                            'row_spacing' => ['type' => 'number'],
                        ],
                    ],

                    'label' => [
                        'label' => 'admin/labels/general.sections.label',
                        'section_key' => 'label',
                        'fields' => [
                            'width' => ['type' => 'number'],
                            'height' => ['type' => 'number'],
                            'border' => ['type' => 'number'],
                            'padding_top' => ['type' => 'number'],
                            'padding_right' => ['type' => 'number'],
                            'padding_bottom' => ['type' => 'number'],
                            'padding_left' => ['type' => 'number'],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function tapeLayoutEditorSections(): array
    {
        return [
            'layout' => [
                'label' => 'admin/labels/general.sections.layout',
                'column_span' => 2,
                'groups' => [
                    'label' => [
                        'label' => 'admin/labels/general.sections.label',
                        'section_key' => 'dimensions',
                        'fields' => [
                            'width' => ['type' => 'number'],
                            'height' => ['type' => 'number'],
                            'label_gap' => ['type' => 'number'],
                        ],
                    ],
                ],
            ],
        ];
    }
}