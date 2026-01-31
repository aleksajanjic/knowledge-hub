<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $programming = Category::create([
            'name' => 'Programming',
            'slug' => Str::slug('programming'),
        ]);
        $design = Category::create([
            'name' => 'Design',
            'slug' => Str::slug('design'),
        ]);
        $devops = Category::create([
            'name' => 'DevOps',
            'slug' => Str::slug('devops'),
        ]);

        $web = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
            'parent_id' => $programming->id,
        ]);

        $mobile = Category::create([
            'name' => 'Mobile Development',
            'slug' => Str::slug('mobile-development'),
            'parent_id' => $programming->id,
        ]);

        $backend = Category::create([
            'name' => 'Backend',
            'slug' => Str::slug('backend'),
            'parent_id' => $web->id,
        ]);

        $frontend = Category::create([
            'name' => 'Frontend',
            'slug' => Str::slug('frontend'),
            'parent_id' => $web->id,
        ]);

        $ui = Category::create([
            'name' => 'UI Design',
            'slug' => Str::slug('ui-design'),
            'parent_id' => $design->id,
        ]);

        $ux = Category::create([
            'name' => 'UX Design',
            'slug' => Str::slug('ux-design'),
            'parent_id' => $design->id,
        ]);
    }
}
