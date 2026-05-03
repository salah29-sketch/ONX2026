<?php

namespace Database\Seeders;

use App\Models\Content\Faq;
use App\Models\Content\Testimonial;
use Illuminate\Database\Seeder;

class FaqTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['question' => 'كيف أعرف أن تاريخ الحفلة متاح؟', 'answer' => 'من صفحة الحجز اختر نوع الخدمة «حفلات» ثم اختر التاريخ من التقويم. الأيام غير المتاحة تظهر بشكل واضح.', 'sort_order' => 1],
            ['question' => 'هل يمكن الحجز للإعلانات بدون تاريخ؟', 'answer' => 'نعم. في صفحة الحجز اختر «إعلانات»، ثم اختر الباقة وأرسل الطلب مباشرة بدون التقويم.', 'sort_order' => 2],
            ['question' => 'هل التأكيد فوري بعد الإرسال؟', 'answer' => 'الإرسال يسجّل طلبك، ثم نقوم بمراجعته والتواصل معك لتأكيد التفاصيل (الموعد/المكان/الملحقات).', 'sort_order' => 3],
            ['question' => 'كيف يتم التواصل بعد الحجز؟', 'answer' => 'نتواصل معك عبر الهاتف أو واتساب حسب المعلومات التي تضعها في النموذج.', 'sort_order' => 4],
            ['question' => 'هل يمكن طلب عرض خاص؟', 'answer' => 'أكيد. اكتب التفاصيل في الملاحظات أثناء الحجز، أو أرسل رسالة عبر صفحة «تواصل معنا».', 'sort_order' => 5],
        ];

        foreach ($faqs as $item) {
            Faq::firstOrCreate(
                ['question' => $item['question']],
                ['answer' => $item['answer'], 'sort_order' => $item['sort_order'], 'is_active' => true]
            );
        }

        if (Testimonial::count() === 0) {
            $testimonials = [
                ['client_name' => 'عميل', 'client_role' => 'عميل — إعلان تجاري', 'subtitle' => 'علامة تجارية', 'content' => '«تعامل احترافي من أول اتصال حتى تسليم المونتاج. الصورة النهائية كانت فوق توقعاتنا والزبائن لاحظوا الفرق.»', 'rating' => 5, 'initial' => 'م', 'sort_order' => 1],
                ['client_name' => 'عروسان', 'client_role' => 'عروسان — تصوير حفل', 'subtitle' => 'مناسبة خاصة', 'content' => '«غطّوا حفل زفافنا بأسلوب سينمائي حقيقي. الإضاءة واللقطات كانت مدروسة والنتيجة نعرضها لكل الضيوف.»', 'rating' => 5, 'initial' => 'س', 'sort_order' => 2],
                ['client_name' => 'منظم فعاليات', 'client_role' => 'منظم فعاليات — باقة حفلات', 'subtitle' => 'فعالية مؤسسية', 'content' => '«التنظيم والالتزام بالوقت ممتاز. تواصلوا معنا قبل الموعد ووضحوا كل التفاصيل. ننصح بهم بدون تردد.»', 'rating' => 5, 'initial' => 'خ', 'sort_order' => 3],
            ];
            foreach ($testimonials as $t) {
                Testimonial::create(array_merge($t, ['is_active' => true]));
            }
        }
    }
}
