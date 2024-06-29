<?php

namespace Database\Seeders;

use Bouncer;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Contract;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
class CreateRolesAndAbilitiesSeeder extends Seeder
{
    public function run()
    {

         $roles = [
            'doctor'=>'طبيب/ة' ,
            'women-doctor'=>'طبيب/ة نسائية'  ,
            'child-doctor' => 'طبيب/ة أطفال',
            'nutritionist'=>'أخصائي/ة تغذية' ,
            'child-nutritionist'=> 'أخصائي/ة تغذية أطفال' ,
            'women-nutritionist'=> 'أخصائي/ة تغذية نساء' ,
            'receptionist'=>'موظف/ة استقبال' ,
            'statistics-employee'=>'موظف/ة إحصائات' ,
            'pharmacist'=>'صيدلاني/ة' ,
            'health-education'=>'موظف/ة تثقيف صحي',
        ];

        foreach ($roles as $name => $title) {
            Bouncer::role()->firstOrCreate([
                'name' => $name,
                'title' => $title,
            ]);
        }


        $userData = [
            ['name' => 'محمد عز الدين', 'user_name' => 'women-nutritionist', 'role' => 'women-nutritionist'],
            ['name' => 'أحمد عيسى', 'user_name' => 'child-nutritionist', 'role' => 'child-nutritionist'],
            ['name' => 'زيد الفارس', 'user_name' => 'nutritionist', 'role' => 'nutritionist'],
            ['name' => 'رؤى حمدان', 'user_name' => 'women-doctor', 'role' => 'women-doctor'],
            ['name' => 'لين محمد الياسر', 'user_name' => 'child-doctor', 'role' => 'child-doctor'],
            ['name' => 'رهام ماهر', 'user_name' => 'receptionist', 'role' => 'receptionist'],
            ['name' => 'هدى التقي', 'user_name' => 'pharmacist', 'role' => 'pharmacist'],
            ['name' => 'جورج بطرس', 'user_name' => 'health-education', 'role' => 'health-education'],
            ['name' => 'محمد عياش', 'user_name' => 'statistics-employee', 'role' => 'statistics-employee'],
        ];

        $streetNames = [
            'شارع السلام', 'شارع النصر', 'شارع الحرية', 'شارع الملك عبد العزيز',
            'شارع صلاح الدين', 'شارع الملك فهد،' ,'شارع محمد بن عبد العزيز', 'شارع خالد بن الوليد',
            'شارع الملك فيصل', 'شارع الملك عبدالله', 'شارع الملك سلمان', 'شارع الأمير سلطان',
            'شارع الأمير محمد بن فهد', 'شارع الأمير خالد الفيصل', 'شارع الأمير تركي بن عبد العزيز',
            'شارع الأمير نايف بن عبد العزيز', 'شارع الأمير فهد بن عبد العزيز', 'شارع الأمير عبد الرحمن بن عبد العزيز',
        ];



        foreach ($userData as $data) {


            $employeeData = [
                'name' => $data['name'],
                'phone_number' => '1234567890',
                'user_name' => $data['user_name'],
                'password' => Hash::make('secret123'),
                'active' => 1,
                'is_logged' => false
            ];

            $randomStreetIndex = rand(0, count($streetNames) - 1);
            $randomSubdistrictID = rand(1, 9);
            $employee = Employee::firstOrCreate($employeeData);
            $employee->assign($data['role']);
            $addressData = [
                'name' => $streetNames[$randomStreetIndex],
                'subdistrict_id' => $randomSubdistrictID,
            ];
            $employee->addresses()->create($addressData);


            // Generate random expiration date (valid or expired)
            $randomDate = $this->generateRandomDate();

            $contractData = [
                'expiration_date' => $randomDate,
                'contract_value' => 10000,
                'certificate' => 'contract_certificate.pdf',
                'medical_center_id' => 1,
                'is_valid' => true
            ];

            $employee->contracts()->create($contractData);

        }



    }

    private function generateRandomDate()
    {
        // Define start and end timestamps for the desired range (2024-2030)
        $startDateTimestamp = strtotime('2024-01-01');
        $endDateTimestamp = strtotime('2030-12-31');

        // Generate random timestamp within the specified range
        $randomTimestamp = rand($startDateTimestamp, $endDateTimestamp);

        // Format timestamp as date string
        $randomDate = date('Y-m-d', $randomTimestamp);

        return $randomDate;
    }

}
