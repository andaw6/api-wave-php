<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $companyExample = [
            ['type' => 'ELECTRICITY', 'name' => 'Senelec', 'icon' => 'https://www.senelec.sn/assets/uploads/media-uploader/logo1637145113.png'],
            ['type' => 'WATER', 'name' => 'SDE', 'icon' => 'https://upload.wikimedia.org/wikipedia/fr/e/ed/Logo_SDE_S%C3%A9n%C3%A9gal.jpg'],
            ['type' => 'INTERNET', 'name' => 'Sonatel', 'icon' => 'https://orange.africa-newsroom.com/files/large/3a46a62fb05c98e'],
            ['type' => 'RENT', 'name' => 'Société Immobilière du Cap-Vert', 'icon' => 'https://upload.wikimedia.org/wikipedia/fr/6/66/Sicap_logo.jpg'],
            ['type' => 'EDUCATION', 'name' => 'UNCHK', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTHqs7it53GtRsCWrbyRAHc_y1oFKAdVupb_g&s'],
            ['type' => 'EDUCATION', 'name' => 'UCAD', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/8/87/Logo_ucad_2.png'],
            ['type' => 'RESTAURANT', 'name' => 'Le Lagon', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT-X9sx3ebIoldGU_5-3C_lbPqdnFxGmJqBsA&s'],
            ['type' => 'HEALTH', 'name' => 'Clinique Madeleine', 'icon' => 'https://jotalixibar.com/wp-content/uploads/2021/10/clinique-madeleine.png'],
            ['type' => 'ENTERTAINMENT', 'name' => 'Canal+', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/Logo_Canal%2B_1995.svg/2560px-Logo_Canal%2B_1995.svg.png'],
            ['type' => 'TRANSPORT', 'name' => 'Dakar Dem Dikk', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS4N65jrRApaRmsQ12aV3KgLovxi9_uv35Kbg&s'],
            ['type' => 'INSURANCE', 'name' => 'NSIA Assurances', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQwNqDC4R1kYBy4g4WctgYD2GMNJuQYPafiqQ&s'],
            ['type' => 'SHOPPING', 'name' => 'Exclusive Sénégal', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCZHIxqG1oUyTZme6_P46Zpv05lOtGXbTSfA&s'],
            ['type' => 'SUBSCRIPTION', 'name' => 'Orange Money', 'icon' => 'https://play-lh.googleusercontent.com/5bVuQv-mHv8fwgD9xsYklPMVjCWQiKOIZt5GnKIVwwNtHniuZqWnxqJKqpWHlTP7vALZ'],
            ['type' => 'GROCERIES', 'name' => 'Auchan', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkQ3AUgc3dRLVrNDTkjHjgjAfEdjyjH3cr4Q&s'],
            ['type' => 'CHARITY', 'name' => 'Fondation Servir le Sénégal', 'icon' => 'https://www.ndarinfo.com/photo/art/grande/6269909-9389547.jpg?v=1390847181'],
            ['type' => 'BANK', 'name' => 'Ecobank', 'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5fFFZMV1klK3koLiZVkzZYpai8iDY7qRuRw&s']
        ];

        foreach ($companyExample as $company) {
            Company::create($company);
        }
    }
}
