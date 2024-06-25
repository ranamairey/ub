<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $medicines = [
            [
                'name' => 'أموكسيسيلين',
                'scientific_name' => 'مضاد حيوي من فئة البنسلينات',
                'titer' => '500 مجم',
                'code' => 1,
                'unit' => 'مغ',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'بسكويت عالي الطاقة/البروتين',
                'scientific_name' => 'بسكويت غني بالبروتين والطاقة',
                'titer' => '10 جرام بروتين لكل بسكويتة',
                'code' => 1,
                'unit' => 'كرتونة 16',
                'type' => 'Nutrition'
            ],
            [
                'name' => 'محلول كبريتات الحديد الفموي',
                'scientific_name' => 'مكمل غذائي يحتوي على الحديد',
                'titer' => '100 ملجم',
                'code' => 1,
                'unit' => 'ملغ',
                'type' => 'Nutrition'
            ],
            [
                'name' => 'أقراص حمض الفوليك',
                'scientific_name' => 'فيتامين ضروري لتكوين خلايا الدم الحمراء',
                'titer' => '400 ميكروجرام',
                'code' => 1,
                'unit' => 'علبة 1000',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'دهن مدعم',
                'scientific_name' => 'دهن غني بالفيتامينات والمعادن',
                'titer' => 'مختلف حسب النوع',
                'code' => 1,
                'unit' => 'كرتون',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'أقراص حديد 60 ملجم + حمض الفوليك 400 ميكروجرام',
                'scientific_name' => 'مكمل غذائي يحتوي على الحديد وحمض الفوليك',
                'titer' => '60 ملجم حديد + 400 ميكروجرام حمض الفوليك',
                'code' => 1,
                'unit' => 'مضغوطة',
                'type' => 'Nutrition'
            ],
            [
                'name' => 'ميبييندازول',
                'scientific_name' => 'طارد للديدان المعوية',
                'titer' => '200 مجم',
                'code' => 1,
                'unit' => 'علبة-100',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'بودرة الميترونيدازول',
                'scientific_name' => 'مضاد حيوي لعلاج العدوى البكتيرية',
                'titer' => '250 مجم',
                'code' => 1,
                'unit' => 'عبوة',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'أقراص المغذيات الدقيقة للحمل',
                'scientific_name' => 'مكمل غذائي يحتوي على فيتامينات ومعادن ضرورية للحمل',
                'titer' => 'مختلف حسب النوع',
                'code' => 1,
                'unit' => 'مضغوطة',
                'type' => 'Nutrition'
            ],
            [
                'name' => 'بودرة المغذيات الدقيقة المتعددة',
                'scientific_name' => 'مكمل غذائي يحتوي على مجموعة متنوعة من الفيتامينات والمعادن',
                'titer' => 'مختلف حسب النوع',
                'code' => 1,
                'unit' => 'ظرف',
                'type' => 'Nutrition'
            ],
            [
                'name' => 'تعليق نستاتين الفموي',
                'scientific_name' => 'مضاد فطريات لعلاج عدوى الفم',
                'titer' => '100000 وحدة دولية/مل',
                'code' => 1,
                'unit' => 'عبوة',
                'type' => 'Ordinary'
            ],
            [
                'name' => 'إكسير باراسيتامول',
                'scientific_name' => 'مسكن للألم وخافض للحرارة',
                'titer' =>'1000',
                'code' => '1',
                'unit' => 'عبوة60مل',
                'type' => 'Ordinary'
            ],


            [
                'name' => 'دهن علاجي',
                'scientific_name' => '',
                'titer' => '100',
                'code' => '0',
                'unit' => 'TBE-5g',
                'type' => 'Ordinary'
            ],
        ];



        foreach ($medicines as $medicine) {
            DB::table('medicines')->insert($medicine);
        }
    }
}
