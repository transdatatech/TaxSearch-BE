<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = array(
            "Alabama" => "AL",
            "Alaska" => "AK",
            "Arizona" => "AZ",
            "Arkansas" => "AR",
            "California" => "CA",
            "Colorado" => "CO",
            "Connecticut" => "CT",
            "Delaware" => "DE",
            "Florida" => "FL",
            "Georgia" => "GA",
        );

        foreach($states as $key=>$value){
            $state=new State();
            $state->name=$key;
            $state->code=$value;
            $state->price=50.00;
            $state->save();
        }
    }
}
