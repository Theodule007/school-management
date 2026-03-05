<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
            Section::insert([
                ['name' => 'SVT', 'is_default' => true],
                ['name' => 'SMP', 'is_default' => true],
                ['name' => 'SES', 'is_default' => true],
                ['name' => 'LLA', 'is_default' => true],
            ]);
        
    }
}
