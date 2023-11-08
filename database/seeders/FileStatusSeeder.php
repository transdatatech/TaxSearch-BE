<?php

namespace Database\Seeders;

use App\Models\FileStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_status=[
            [
                'name'=>"Pending"
            ],
            [
                'name'=>"Processing"
            ],
            [
                'name'=>"Draft"
            ],
            [
                'name'=>"In Review"
            ],
            [
                'name'=>"Processsed"
            ],

        ];
        foreach($file_status as $status){
            FileStatus::create($status);

        }
    }
}
