<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities=[
            ['name' => 'Reaching children 6-59 months with MNPs\الأطفال الذين تلقوا مغذيات دقيقة'],
            ['name' => 'Reaching pregnant and lactating women (PLWs) with MMNs\الأمهات اللواتي تلقين المغذيات الدقيقة'],
            ['name' => 'Screening children 6-59 months for acute malnutrition\المسح التغذوي للأطفال'],
            ['name' => 'Screening pregnant and lactating women (PLWs) for acute malnutrition\المسح التغذوي للحوامل والمرضعات'],
            ['name' => 'Admit children 6-59 months for SAM treatment - outpatient\قبول الأطفال للعلاج من سوء تغذية الشديد - خارجي'],
            ['name' => 'Nutrition - Conduct community-based dialogues including awareness-raising sessions (individual), consultation sessions, Help desk (center-based and mobile)\إجراء حوارات مجتمعية ومن ضمنها جلسات توعية (فردية)، جلسات استشارية، مراكز المساعدة(مركزية ومتنقلة)'],
            ['name' => 'Nutrition - Reach beneficiaries with C4D door-to-door, and tent to tent community mobilization\الوصول للمستفيدين طريق حملات التوعية في المنازل والخيم'],
            ['name' => 'Health facility supported by unicef SAM - outpatient treatment programme\المراكز الصحية المدعومة من برنامج يونيسف لعلاج سوء التغذية الشديد - خارجي'],
            ['name' => 'Health facility supported by unicef SAM treatment programme following SPHERE standards\المراكز الصحية المدعومة من برنامج يونيسف لعلاج سوء التغذية الشديد - خارجي والتي تحقق المعايير القياسية'],
            ['name' => 'Train health staffs on CMAM guidelines\تدريب العاملين الصحيين حول التدبير المجتمعي لسوء التغذية'],
            ['name' => 'Train health workers on IYCF counselling\تدريب العاملين الصحيين حول تغذية الرضع وصغار الأطفال'],
            ['name' => 'Train health and community workers on IYCF C4D\تدريب العاملين الصحيين والمجتمعيين على التواصل من أجل التنمية حول تغذية الرضع وصغار الأطفال'],
            ['name' => 'Nutrition - Conduct advocacy meetings with influencers, community leaders and decision-makers\إجراء اجتماعات مناصرة مع المؤثرين المجتمعيين وأصحاب القرار'],
            ['name' => 'Reaching children 6-59 months with Vit A supplementation\الأطفال الذين تلقوا فيتامين آ'],
            ['name' => 'Provide most venerable population with nutrition supplies\توفير المواد التغذوية للتجمعات السكانية الأكثر عرضة للخطر'],
            ['name' => 'Reaching children 6-36 months with Lipid based nutrients\الأطفال (6-36 شهر) الذين تلقوا المواد الغذائية دهنية الأساس'],
            ['name' => 'Reaching children 37-59 months with high energy biscuit\الأطفال (37-59 شهر) الذين تلقوا البسكويت عالي الطاقة'],
            ['name' => 'Reaching pregnant and lactating women (PLWs) with high energy biscuit\الحوامل والمرضعات اللواتي تلقين البسكويت عالي الطاقة'],
            ['name' => 'Train SAM children caregivers on MUAC Screening and referrals in IDP camps\تدريب مقدمي الرعاية للأطفال الذين يعانون من سوء التغذية الحاد الشديد على قياس محيط العضد والإحالة في المخيمات'],
            ['name' => 'Nutrition - Conduct community-based dialogues including awareness-raising sessions (group), consultation sessions, Help desk (center-based and mobile)\إجراء حوارات مجتمعية ومن ضمنها جلسات توعية (جماعية)، جلسات استشارية، مراكز المساعدة(مركزية ومتنقلة)'],
            ['name' => 'IYCF beneficiaries who scored a higher result in the post test\المستفيدين من رسائل التوعية حول تغذية الصغار والرضع الذين حققوا نتائج افضل في الاختبار بعد جلسة التوعية'],
            ['name' => 'IYCF beneficiaries who took a pre and post-test\المستفيدين من رسائل التوعية حول تغذية الصغار والرضع الذين خضعوا لاختبار قبل وبعد جلسة التوعية'],
        ];
        DB::table('activities')->insert($activities);

    }
}
