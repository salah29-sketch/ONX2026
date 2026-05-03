# تنظيم النماذج (Models)

تم ترتيب النماذج في مجلدات فرعية حسب المجال (Domain)، مع استخدام مساحات أسماء فرعية.

---

## الهيكل الحالي

```
app/Models/
├── Admin/           # الإدارة والمستخدمون
│   ├── User.php
│   ├── Role.php
│   ├── Permission.php
│   └── Employee.php
├── Booking/         # الحجوزات
│   ├── Booking.php
│   ├── BookingFile.php
│   ├── BookingPayment.php
│   └── BookingPhoto.php
├── Client/          # العميل ومساحته
│   ├── Client.php
│   ├── ClientMessage.php
│   ├── ClientMessagesSeen.php
│   ├── ClientMediaSeen.php
│   ├── ClientPhoto.php
│   ├── ClientFile.php
│   └── ClientSelectedPhoto.php
├── Event/           # الفعاليات والعروض
│   ├── EventPackage.php
│   ├── EventLocation.php
│   └── AdPackage.php
├── Content/         # المحتوى والموقع
│   ├── Company.php
│   ├── EditableContent.php
│   ├── Faq.php
│   ├── PortfolioItem.php
│   └── Testimonial.php
└── README.md
```

---

## مساحات الأسماء (Namespaces)

| المجلد   | Namespace          | أمثلة استيراد                    |
|----------|--------------------|-----------------------------------|
| Admin/   | `App\Models\Admin` | `use App\Models\Admin\User;`      |
| Booking/ | `App\Models\Booking` | `use App\Models\Booking\Booking;` |
| Client/  | `App\Models\Client`  | `use App\Models\Client\Client;`   |
| Event/   | `App\Models\Event`   | `use App\Models\Event\AdPackage;` |
| Content/ | `App\Models\Content`  | `use App\Models\Content\Faq;`      |

---

## العلاقات بين المجموعات

- **Booking** يعتمد على: Client, Event (EventLocation, EventPackage, AdPackage).
- **Client** يعتمد على: Booking, Content (Testimonial).
- **Content\Testimonial** يعتمد على: Client, Booking.
- **Event** (EventPackage, AdPackage) يعتمد على: Booking (علاقة hasMany).

---

## الإعداد (config/auth.php)

- حراس تسجيل الدخول يستخدمون:
  - `App\Models\Admin\User::class` لمزود `users`
  - `App\Models\Client\Client::class` لمزود `clients`
