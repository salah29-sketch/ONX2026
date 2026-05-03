<?php

use App\Models\Content\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
           Company::create([
            'company_name' => 'ONX' ,
            'address' => 'Sidi Bel Abbes',
            'phone' => '0540 57 35 18',
            'email' => 'contact@onx-edge.com',
            'facebook' => 'https://www.facebook.com/onx',
            'instagram' => 'https://www.instagram.com/onx',
            'twitter' => 'https://twitter.com/onx',
            'linkedin' => 'https://www.linkedin.com/company/onx',
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
        ]);
    }
}
