<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChildTreatmentProgramSeeder extends Seeder
{
  public function run()
  {
    $medicalRecordIds = DB::table('medical_records')->where('category', 'child')->pluck('id');

    $acceptanceReasons = [
      'تحسن الحالة الصحية للطفل ',
      'تحقيق وزن مستهدف سابقًا ',
      'إكمال برنامج علاج غذائي سابق ',
      'تحويل من برنامج علاجي آخر ',
      'رغبة الأهل في استمرار العلاج ',
      'اكتشاف سوء تغذية حاد',
    ];


    for ($i = 0; $i < 10; $i++) {
      DB::table('child_treatment_programs')->insert([
        'medical_record_id' => rand(1, 5),
        'employee_id' => 1,
        'employee_choise_id' => 1,
        'program_type' => ['tsfp', 'otp'][array_rand(['tsfp', 'otp'])],
        'acceptance_reason' => $acceptanceReasons[array_rand($acceptanceReasons)],
        'acceptance_party' => ['another-TSFP', 'OTP', 'Re-acceptance', 'SC', 'Community'][array_rand(['another-TSFP', 'OTP', 'Re-acceptance', 'SC', 'Community'])],
        'acceptance_type' => ['new', 'old'][array_rand(['new', 'old'])],
        'target_weight' => 50,
        'measles_vaccine_received' => rand(0, 1) > 0.5 ? true : false,
        'measles_vaccine_date' => '2022-8-8',
        'date' => Carbon::now()->format('Y-m-d'),
        'created_at' => Carbon::now()->addHours($i),
      ]);
    }
  }
}
