<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AbUser;
use App\Models\AbArticle;
use App\Models\AbArticleCategory;

class DevelopmentData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Für die User
        AbUser::truncate();
        $csv = fopen(base_path('data\user.csv'),'r');
        $firstLine = true;
        while (($data = fgetcsv($csv,999,';')) !== false)
        {
            if(!$firstLine)
            {
                $p = AbUser::create([
                    'ab_name' => $data['1'],
                    'ab_password' => $data['2'],
                    'ab_mail' => $data['3'],
                ]);
                $p->id = $data['0'];
                $p->save();
            }
            $firstLine = false;
        }
        fclose($csv);


        // Für die Artikel
        AbArticle::truncate();
        $csv = fopen(base_path('data\articles.csv'),'r');
        $firstLine = true;
        while (($data = fgetcsv($csv,999,';')) !== false)
        {
            if(!$firstLine)
            {
                $price = str_replace('.','',$data['2']);
                $p = AbArticle::create([
                    'ab_name' => $data['1'],
                    'ab_price' => ($price * 1000),
                    'ab_description' => $data['3'],
                    'ab_creator_id' => $data['4'],
                    'ab_createdate' => $data['5'],
                ]);
                $p->id = $data['0'];
                $p->save();
            }
            $firstLine = false;
        }
        fclose($csv);



        // Für die Artikel Kategorien
        AbArticleCategory::truncate();
        $csv = fopen(base_path('data\articlecategory.csv'),'r');
        $firstLine = true;
        while (($data = fgetcsv($csv,500,';')) !== false)
        {
            if ($data['2'] == "NULL")
            {
                $data['2'] = NULL;
            }

            if(!$firstLine)
            {
                $p = AbArticleCategory::create([
                    'ab_name' => $data['1'],
                    'ab_description' => 'Es existiert keine Beschreibung.',
                    'ab_parent' => $data['2'],
                ]);
                $p->id = $data['0'];
                $p->save();
            }
            $firstLine = false;
        }
        fclose($csv);
    }
}
