<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Label;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $continents = Http::get('http://country.io/continent.json')->json();
        foreach ($continents as $iso => $continent){
            DB::table('continents')->insert([
                'continent' => $continent,
                'id' => $iso,
            ]);
        }

        $countries = Http::get('http://country.io/names.json')->json();
        foreach ($countries as $iso => $country){
            DB::table('countries')->insert([
                'country' => $country,
                'continent_id' => $iso,
            ]);
        }

        User::factory()->count(10)->create()->each(function ($user){
            Project::factory()->count(rand(1, 5))->create(['user_id' => $user->id])->each(function ($project) use ($user) {
                $project->links()->attach(User::where('id', '<>', $user->id)->pluck('id')->shuffle()->chunk(rand(3, 5))->first());
            });

            Label::factory()->count(rand(1, 3))->create(['user_id' => $user->id])->each(function ($label) use ($user){
                $label->projects()->attach(Project::where('user_id', $user->id)->pluck('id')->shuffle()->chunk(rand(3, 5))->first());
            });
        });

        $user = User::find(1);
        $user->is_admin = 1;
        $user->save();
    }
}
