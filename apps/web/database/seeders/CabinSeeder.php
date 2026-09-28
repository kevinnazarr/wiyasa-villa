<?php

namespace Database\Seeders;

use App\Models\Cabin;
use Illuminate\Database\Seeder;

class CabinSeeder extends Seeder
{
    /**
     * Seed 10 baseline cabins with id/en translations for manual testing.
     *
     * Idempotent: safe to re-run via updateOrCreate on code/slug.
     */
    public function run(): void
    {
        foreach ($this->cabins() as $data) {
            $cabin = Cabin::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name_id'],
                    'slug' => $data['slug'],
                    'description' => $data['description_id'],
                    'capacity' => 7,
                    'base_occupancy' => 4,
                    'status' => $data['status'],
                ]
            );

            $cabin->translations()->updateOrCreate(
                ['locale' => 'id'],
                ['name' => $data['name_id'], 'description' => $data['description_id']]
            );

            $cabin->translations()->updateOrCreate(
                ['locale' => 'en'],
                ['name' => $data['name_en'], 'description' => $data['description_en']]
            );
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function cabins(): array
    {
        return [
            [
                'code' => 'WY-01',
                'slug' => 'arjuna',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Arjuna',
                'name_en' => 'Arjuna Cabin',
                'description_id' => 'Kabin dengan pemandangan langsung ke kompleks Candi Arjuna, cocok untuk keluarga.',
                'description_en' => 'Cabin overlooking the Arjuna Temple complex, ideal for families.',
            ],
            [
                'code' => 'WY-02',
                'slug' => 'srikandi',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Srikandi',
                'name_en' => 'Srikandi Cabin',
                'description_id' => 'Kabin hangat dengan perapian, dua menit jalan kaki ke Kawah Sikidang.',
                'description_en' => 'Cozy cabin with a fireplace, a two-minute walk to Sikidang Crater.',
            ],
            [
                'code' => 'WY-03',
                'slug' => 'puntadewa',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Puntadewa',
                'name_en' => 'Puntadewa Cabin',
                'description_id' => 'Kabin terbesar di barisan depan dengan dek sunrise menghadap Telaga Warna.',
                'description_en' => 'Largest front-row cabin with a sunrise deck facing Telaga Warna lake.',
            ],
            [
                'code' => 'WY-04',
                'slug' => 'nakula',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Nakula',
                'name_en' => 'Nakula Cabin',
                'description_id' => 'Kabin compact untuk pasangan, dikelilingi kebun kentang khas Dieng.',
                'description_en' => 'Compact cabin for couples, surrounded by Dieng potato fields.',
            ],
            [
                'code' => 'WY-05',
                'slug' => 'sadewa',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Sadewa',
                'name_en' => 'Sadewa Cabin',
                'description_id' => 'Kembaran Kabin Nakula dengan interior hangat dan dapur kecil lengkap.',
                'description_en' => 'Twin of Nakula cabin with warm interiors and a small full kitchen.',
            ],
            [
                'code' => 'WY-06',
                'slug' => 'bima',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Bima',
                'name_en' => 'Bima Cabin',
                'description_id' => 'Kabin kokoh dekat gerbang Bukit Sikunir, favorit para pemburu sunrise.',
                'description_en' => 'Sturdy cabin near the Sikunir Hill gate, a favorite of sunrise chasers.',
            ],
            [
                'code' => 'WY-07',
                'slug' => 'gatotkaca',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Gatotkaca',
                'name_en' => 'Gatotkaca Cabin',
                'description_id' => 'Kabin dua lantai dengan balkon luas menghadap hamparan carica.',
                'description_en' => 'Two-storey cabin with a wide balcony overlooking carica orchards.',
            ],
            [
                'code' => 'WY-08',
                'slug' => 'semar',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Semar',
                'name_en' => 'Semar Cabin',
                'description_id' => 'Kabin tenang di sudut paling sepi, cocok untuk retreat dan kerja remote.',
                'description_en' => 'Quiet cabin in the most secluded corner, perfect for retreats and remote work.',
            ],
            [
                'code' => 'WY-09',
                'slug' => 'petruk',
                'status' => 'MAINTENANCE',
                'name_id' => 'Kabin Petruk',
                'name_en' => 'Petruk Cabin',
                'description_id' => 'Sedang dalam perawatan berkala, segera kembali tersedia.',
                'description_en' => 'Currently under scheduled maintenance, back soon.',
            ],
            [
                'code' => 'WY-10',
                'slug' => 'bagong',
                'status' => 'ACTIVE',
                'name_id' => 'Kabin Bagong',
                'name_en' => 'Bagong Cabin',
                'description_id' => 'Kabin ceria dekat area api unggun dan taman bermain anak.',
                'description_en' => 'Cheerful cabin near the bonfire area and kids playground.',
            ],
        ];
    }
}
