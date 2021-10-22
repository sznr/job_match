<?php

namespace Database\Seeders;

use App\Models\Occupation;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Occupation::create(['name' => 'フロントエンドエンジニア']);
        Occupation::create(['name' => 'サーバーサイドエンジニア']);
        Occupation::create(['name' => 'デザイナー']);
        Occupation::create(['name' => 'プロジェクトリーダー']);
        Occupation::create(['name' => 'プロダクトマネージャー']);
    }
}
