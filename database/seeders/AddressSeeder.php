<?php
namespace Database\Seeders;

use App\Models\Address;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define street names array
        $streetNames = [
            'شارع السلام', 'شارع النصر', 'شارع الحرية', 'شارع الملك عبد العزيز',
            'شارع صلاح الدين', 'شارع الملك فهد،' ,'شارع محمد بن عبد العزيز', 'شارع خالد بن الوليد',
            'شارع الملك فيصل', 'شارع الملك عبدالله', 'شارع الملك سلمان', 'شارع الأمير سلطان',
            'شارع الأمير محمد بن فهد', 'شارع الأمير خالد الفيصل', 'شارع الأمير تركي بن عبد العزيز',
            'شارع الأمير نايف بن عبد العزيز', 'شارع الأمير فهد بن عبد العزيز', 'شارع الأمير عبد الرحمن بن عبد العزيز',
        ];

        // Get all medical records
        $medicalRecords = MedicalRecord::all();

        foreach ($medicalRecords as $medicalRecord) {
            // Generate random street name index
            $randomStreetIndex = rand(0, count($streetNames) - 1);

            // Generate random subdistrict ID (1 to 9)
            $randomSubdistrictID = rand(1, 9);

            Address::create([
                'name' => $streetNames[$randomStreetIndex],
                'subdistrict_id' => $randomSubdistrictID,
                'addressable_id' => $medicalRecord->id,
                'addressable_type' => 'App\Models\MedicalRecord',
            ]);
        }
    }
}
