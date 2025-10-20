<?php

namespace Database\Seeders\Base;

use App\Models\Base\{
    Country,
    Provider,
};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->names as $providerData) {
            Provider::create([
                'name'         => $providerData['name'],
                'slug'         => Str::slug($providerData['name']),
                'contact'      => $providerData['contact'] ?: 'Sin Datos',
                'email'        => $providerData['email'] ?: 'Sin Datos',
                'phone'        => $providerData['phone'] ?: 'Sin Datos',
                'url'          => $providerData['url'] ?: 'Sin Datos',
                'country_id' => Country::where('code', $providerData['country_id'])->first()?->id ?? 1,
            ]);
        }
    }

    private $names = [
        [
            'name'          => 'Jessica Angel',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'PROVEEDOR 2',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'Ebay',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'AAKAA',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'SOIEBLU',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'Cinderella Divine',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'IF BY SEA',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'Cam',
            'contact'       => 'Cefian',
            'email'         => '',
            'phone'         => '(213) 745-4125 whatsapp (213) 377-7321',
            'url'           => 'https://www.fashiongo.net/cameo',
            'country_id'  => 'Estados Unidos'
        ],
        [
            'name'          => 'Luxxel Inc.',
            'contact'       => 'Luxxel',
            'email'         => 'info@luxxel.clothing',
            'phone'         => '213-986-4884',
            'url'           => 'Fashion Go',
            'country_id'  => 'USA'
        ],
        [
            'name'          => 'CEFIAN',
            'contact'       => 'info@cefian.co',
            'email'         => 'info@cefian.co',
            'phone'         => 'NA',
            'url'           => 'Fashiongo.net',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Maniju Fashion',
            'contact'       => 'NA',
            'email'         => 'info@manijuusa.com',
            'phone'         => '2134366564',
            'url'           => 'www.manijuusa.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Lenovia',
            'contact'       => 'NA',
            'email'         => 'lenovia1@gmail.com',
            'phone'         => '2137474477',
            'url'           => 'www.lenoviausa.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'VAVA VOOM',
            'contact'       => '',
            'email'         => 'vavaboomglamfactory@gmail.com',
            'phone'         => '2137429926',
            'url'           => 'www.lashroom.com/vavavoom',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Symphony',
            'contact'       => '',
            'email'         => 'showroom@symphonyfashion.com',
            'phone'         => '2137483346',
            'url'           => 'www.symphonyfashion.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Lexi Clothing',
            'contact'       => 'sales',
            'email'         => 'sales@lexiclothing.com.au',
            'phone'         => '+61 883731109',
            'url'           => 'lexiclothing.com.au',
            'country_id'  => 'AUS',
        ],
        [
            'name'          => 'DRESS THE POPULATION',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
        [
            'name'          => 'SONYA',
            'contact'       => 'Sonya',
            'email'         => 'sonya@sonya.moda',
            'phone'         => 'NA',
            'url'           => 'www.sonya.moda',
            'country_id'  => 'AUS',
        ],
        [
            'name'          => 'PremGroup',
            'contact'       => 'runwaylabel',
            'email'         => 'admin@runawaythelabel.com',
            'phone'         => 'NA',
            'url'           => 'https://runawaythelabel.com/',
            'country_id'  => 'AUS',
        ],
        [
            'name'          => 'Minuet',
            'contact'       => '',
            'email'         => 'sales1@minuetonline.com',
            'phone'         => '213-746-1081',
            'url'           => 'http://www.minuetonline.com/',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Ricarica',
            'contact'       => 'sales@ricaricainc.com',
            'email'         => 'sales@ricaricainc.com',
            'phone'         => '213-205-8919',
            'url'           => '',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'DO AND BE',
            'contact'       => 'AURA',
            'email'         => 'SHOWROOM@DOANDBECOLLECTION.COM',
            'phone'         => '',
            'url'           => 'FASHIONGO',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Miss Circle',
            'contact'       => 'Chat en Fashiongo',
            'email'         => 'Chat en Fashiongo',
            'phone'         => '6467149428',
            'url'           => 'www.fashiongo.net/misscircle',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'WFFS',
            'contact'       => 'NA',
            'email'         => 'CONTACT@WISHFORFALLINGSTAR.COM',
            'phone'         => 'NA',
            'url'           => 'http://wishforfallingstar.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'DressDay',
            'contact'       => 'USA',
            'email'         => 'costumer@dressdayusa.com',
            'phone'         => '2136602280',
            'url'           => 'FashionGo',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Elle Zeitoune',
            'contact'       => 'Anika',
            'email'         => 'enquiries@ellezeitoune.com.au',
            'phone'         => 'NA',
            'url'           => 'https://ellezeitoune.com.au/',
            'country_id'  => 'AUS',
        ],
        [
            'name'          => 'Dress Forum',
            'contact'       => 'NA',
            'email'         => 'dress forum',
            'phone'         => '213-493-4504',
            'url'           => 'dress forum',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'YUMI KIM',
            'contact'       => 'YUMI KIM',
            'email'         => 'NA',
            'phone'         => 'NA',
            'url'           => 'www.yumikim.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Ali Express',
            'contact'       => 'NA',
            'email'         => 'NA',
            'phone'         => 'NA',
            'url'           => 'www.aliexpress.com',
            'country_id'  => '',
        ],
        [
            'name'          => 'MONIQUE',
            'contact'       => 'NA',
            'email'         => 'NA',
            'phone'         => 'NA',
            'url'           => 'https://moniquelhuillier.com/',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'ANDREA & LEO COUTURE',
            'contact'       => 'Magaly',
            'email'         => 'na',
            'phone'         => '+1 (213) 342-3781',
            'url'           => 'https://www.andrealeocouture.com/home/',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Rubber Ducky Productions',
            'contact'       => 'NA',
            'email'         => 'NA',
            'phone'         => '2137441228',
            'url'           => 'rubberducky.us',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'Eureka Fashion',
            'contact'       => '',
            'email'         => 'info@fashioneureka.com',
            'phone'         => '213-706-4662',
            'url'           => 'www.fashioneureka.com',
            'country_id'  => 'USA',
        ],
        [
            'name'          => 'NOX ANABEL',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => 'www.noxanabel.com',
            'country_id'  => '',
        ],
        [
            'name'          => 'AMELIA COUTURE',
            'contact'       => '',
            'email'         => '',
            'phone'         => '',
            'url'           => '',
            'country_id'  => '',
        ],
    ];
}
