<?php

use Illuminate\Database\Seeder;

class DiscrepanciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Discrepancia::class,20)->create();
    }
}
