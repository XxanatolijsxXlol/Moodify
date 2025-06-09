<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    public function run()
    {
        Theme::create([
            'name' => 'Green Theme',
            'description' => 'Green theme',
            'css_path' => 'css/themes/green.css',
            'thumbnail' => 'storage/themes/green-thumbnail.png',
        ]);

        Theme::create([
            'name' => 'Red Theme',
            'description' => 'Red theme',
            'css_path' => 'css/themes/red.css',
            'thumbnail' => 'storage/themes/red-thumbnail.png',
        ]);
    }
}