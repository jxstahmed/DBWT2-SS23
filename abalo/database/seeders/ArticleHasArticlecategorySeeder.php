<?php

namespace Database\Seeders;

use App\Models\AbArticleHasArticleCategory;
use Illuminate\Database\Seeder;

class ArticleHasArticlecategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AbArticleHasArticleCategory::truncate();
        $csv = fopen(base_path('data/article_has_articlecategory.csv'), 'r');
        $firstLine = true;
        while (($data = fgetcsv($csv, 999, ';')) !== false)
        {
            if (!$firstLine)
            {
                AbArticleHasArticleCategory::create([
                    'ab_articlecategory_id' => $data['0'],
                    'ab_article_id' => $data['1']
                ]);
            }
            $firstLine = false;
        }
        fclose($csv);
    }
}
