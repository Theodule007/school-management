<?php
namespace Database\Seeders;

use App\Models\Student;
use App\Models\Level;
use App\Models\LevelSection;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $level = Level::where('name', 'NS3')->first();

        $levelSection = LevelSection::where('level_id', $level->id)->first();

        Student::create([
            'nisu' => 'JEAP19960211G1500001',
            'first_name' => 'Jean',
            'last_name' => 'Pierre',
            'gender' => 'M',
            'birth_date' => '1996-02-11',
            'status' => 'active',
            'section_id' => $levelSection->section_id
        ]);
    }
}