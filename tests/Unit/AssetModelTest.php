<?php

namespace Tests\Unit;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use Tests\TestCase;

class AssetModelTest extends TestCase
{
    public function test_an_asset_model_contains_assets()
    {
        $category = Category::factory()->create([
            'category_type' => 'asset',
        ]);
        $model = AssetModel::factory()->create([
            'category_id' => $category->id,
        ]);

        $asset = Asset::factory()->create([
            'model_id' => $model->id,
        ]);
        $this->assertEquals(1, $model->assets()->count());
    }
}
