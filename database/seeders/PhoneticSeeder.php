<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class PhoneticSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::transaction(
            function () {

                $categories = Category::all();
                // foreach($posts as $post) { }
                $categories->each( function ( $category ) {
                    // phonetic name
//                    $category->phonetic_name = metaphone( $category->name );


//                    $category->save();
                } );
            }, 1
        );
    }
}
