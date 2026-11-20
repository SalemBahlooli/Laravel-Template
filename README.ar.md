<div dir="rtl">

# قالب Laravel

تطبيق Laravel 13 جاهز تبني عليه أي نظام داخلي. فيه تسجيل الدخول، المستخدمون، المجموعات والصلاحيات، الإعدادات، الإشعارات، سجل النشاط وسجل الدخول، النسخ الاحتياطي، واجهة API، ودعم العربية والإنجليزية مع اتجاه من اليمين لليسار. وفيه أمر واحد ينشئ لك قسماً كاملاً (إضافة / تعديل / حذف / عرض).

ابدأ كل مشروع جديد من هنا، ووجّه وقتك للميزات الخاصة بالمشروع نفسه.

**[English → README.md](README.md)**

---

## ماذا يحتوي القالب

| الجزء | المحتوى |
|---|---|
| **تسجيل الدخول** | بالبريد أو اسم المستخدم مع كلمة المرور، "تذكرني"، قفل مؤقت بعد محاولات فاشلة متكررة، استعادة كلمة المرور برابط **أو** برمز تطبيق المصادقة، تسجيل ذاتي اختياري، الدخول بحساب Microsoft |
| **التحقق بخطوتين** | تطبيق مصادقة (TOTP). الوضع: مطفأ / اختياري / إجباري للجميع. المتصفح الموثوق يُعفى من الرمز 30 يوماً. الدخول بالرمز وحده بدون كلمة مرور. الرمز المستخدم مرة لا يُقبل مرة ثانية |
| **المستخدمون** | قائمة، بحث، فلاتر، إضافة، تعديل، صورة، إيقاف الحساب، حذف ناعم مع استرجاع، تصدير CSV، بريد ترحيب، صلاحيات لكل مستخدم، عرض أجهزة الشخص وتسجيل خروجها |
| **المجموعات والصلاحيات** | شجرة صلاحيات في `config/permission_tree.php`. لكل صلاحية: سماح / منع / وراثة، على مستوى المجموعة أو المستخدم. صلاحيات أب وأبناء، ومستخدم أعلى (Super User)، ومنع أي شخص من منح صلاحية لا يملكها |
| **لوحة التحكم** | أرقام، رسم بياني لنشاط 14 يوماً، المتصلون الآن، آخر النشاطات |
| **الإعدادات** | صفحات تُبنى تلقائياً من `config/settings.php`: عام، الهوية (شعار ولون)، الأمان، الحسابات، البريد مع رسالة تجربة، الدخول الموحد، مدة الاحتفاظ بالسجلات، النسخ الاحتياطي، معلومات النظام |
| **الإشعارات** | جرس بعداد يتحدث تلقائياً، صفحة إشعارات، إعلانات عامة لها وقت بداية ونهاية، صفحة "ما الجديد" |
| **السجلات** | سجل نشاط لكل تعديل (القيمة القديمة ← الجديدة)، سجل دخول مع بريد عند الدخول من جهاز جديد وتنبيه عند تكرر القفل، حالة الاتصال المباشرة (متصل / نشط) |
| **النسخ الاحتياطي** | قاعدة البيانات مع الملفات المرفوعة، بجدول تحدده من الإعدادات، تنزيل وحذف، وكلمة مرور اختيارية للملف |
| **API** | رموز وصول شخصية (Laravel Sanctum) تحت `/api/v1`. الرمز لا يقدر إلا على ما يقدر عليه صاحبه |
| **اللغات** | عربي وإنجليزي مع واجهة كاملة من اليمين لليسار. كل شخص يختار لغته ومنطقته الزمنية |
| **أدوات المطور** | `make:module`، `lang:missing`، `permissions:check`، `app:create-admin`، 114 اختباراً، فحص تلقائي على GitHub Actions، Docker للتطوير وللإنتاج، إعداد Railway |

التقنيات: Laravel 13، PHP 8.3 فأحدث (8.5 داخل Docker)، MySQL 8.4 (ويعمل أيضاً مع PostgreSQL وSQLite)، Blade، Tailwind CSS 4، Alpine.js، Chart.js، Font Awesome 7، Vite 8.

---

## التشغيل بـ Docker (الطريقة المقترحة)

تحتاج فقط [Docker Desktop](https://www.docker.com/products/docker-desktop/).

</div>

```bash
git clone https://github.com/SalemBahlooli/Laravel-Template.git my-app
cd my-app
cp .env.example .env
docker compose up -d --build
docker compose exec app npm install
docker compose exec app npm run build
```

<div dir="rtl">

افتح **http://localhost:8080** وادخل بـ `admin@example.com` / `password`.
تتحكم بهذه البيانات من `ADMIN_EMAIL` و`ADMIN_PASSWORD` في ملف `.env`، وتُستخدم مرة واحدة فقط وقاعدة البيانات خالية من المستخدمين.
**غيّر كلمة المرور فوراً.**

| الرابط | ما هو |
|---|---|
| http://localhost:8080 | التطبيق |
| http://localhost:8025 | Mailpit: كل رسالة يرسلها التطبيق تظهر هنا |
| `localhost:33060` | MySQL (القاعدة والمستخدم `app`، كلمة المرور `secret`) لبرامج قواعد البيانات |

إذا كان المنفذ مستخدماً، ضع مثلاً `APP_PORT=8081` (وكذلك `MAILPIT_PORT` و`DB_FORWARD_PORT` و`VITE_PORT`) قبل `docker compose up`.

الحاوية تنشئ الجداول وتعبّئ البيانات الأساسية وتشغّل المجدول وطابور المهام تلقائياً.
للتحديث الفوري للصفحات أثناء التعديل: `docker compose exec app npm run dev -- --host`.

**السرعة على Windows وmacOS:** المجلدان `vendor/` و`node_modules/` محفوظان في Docker volumes وليس في مجلد المشروع، لأن قراءتهما من مجلد Windows كانت تجعل كل صفحة تأخذ عدة ثوانٍ. لذلك شغّل `composer` و`npm` **داخل** الحاوية (مثلاً `docker compose exec app composer require ...`). ولسرعة أكبر على Windows ضع المشروع داخل WSL بدل `C:\`.

أوامر يومية:

</div>

```bash
docker compose exec app php artisan test           # الاختبارات
docker compose exec app php artisan migrate        # الجداول الجديدة
docker compose exec app php artisan db:seed --class=DemoSeeder   # مستخدمون وسجلات تجريبية
docker compose exec app vendor/bin/pint            # تنسيق الكود
docker compose logs -f app                         # السجلات
```

<div dir="rtl">

## التشغيل بدون Docker

تحتاج PHP 8.3 فأحدث مع الإضافات `pdo_mysql` (أو `pdo_sqlite`) و`intl` و`gd` و`zip` و`bcmath`، وComposer، وNode 22.

</div>

```bash
composer setup        # التثبيت، .env، المفتاح، الجداول والبيانات، بناء الواجهة
composer dev          # الخادم وطابور المهام والسجلات وVite معاً
php artisan schedule:work   # في نافذة ثانية: النسخ الاحتياطي وتنظيف السجلات
```

<div dir="rtl">

لاستخدام SQLite ضع `DB_CONNECTION=sqlite` في `.env` واحذف باقي أسطر `DB_*`.

---

## بدء مشروع جديد من القالب

1. في GitHub اضغط **Use this template**، أو انسخ المستودع وارفعه لمستودع جديد.
2. طبّق قائمة التخصيص في **[docs/getting-started.md](docs/getting-started.md)**: الاسم، `composer.json`، الألوان، الشعار، اللغات، أول مدير.
3. أضف أول قسم في مشروعك:

</div>

```bash
php artisan make:module Project --fields="name:string,budget:decimal:nullable,starts_on:date:nullable,is_done:boolean" --icon=fa-diagram-project
php artisan migrate
php artisan lang:missing ar --write    # ثم ترجم النصوص الجديدة في lang/ar.json
```

<div dir="rtl">

   يتولّد لك: Model، Migration، Factory، Controller مع التحقق من المدخلات، صفحات القائمة والنموذج والتفاصيل، المسارات، أربع صلاحيات، عنصر في القائمة الجانبية، تسجيل النشاط، واختبار ناجح.

4. من **المجموعات والصلاحيات ← Modules** أعطِ مجموعة الصلاحيات الجديدة.

## أين أضع ماذا؟ (ملخص سريع)

| أريد… | المكان |
|---|---|
| قسماً جديداً فيه قائمة ونموذج وتفاصيل | `php artisan make:module` |
| صلاحية جديدة | `config/permission_tree.php`، ثم `php artisan permissions:check` |
| حماية صفحة أو زر | `->middleware('can:invoices.view')` في المسار، و`@can('invoices.approve')` في Blade |
| قيمة يغيرها المدير بدون نشر جديد | `config/settings.php`، وتقرأها بـ `setting('key')` |
| عنصراً في القائمة الجانبية | `config/menu.php` |
| تسجيل حدث في سجل النشاط | `use LogsActivity;` في الـ Model، أو `Activity::log('approved', $invoice)` |
| إشعاراً لشخص | `$user->notify(new SimpleNotification('العنوان', 'النص', $url))` |
| نصاً يظهر للمستخدم | اكتبه بالإنجليزية داخل `__('...')`، ثم `php artisan lang:missing ar --write` وترجمه في `lang/ar.json` |
| مسارات قسم جديد | ملف في `routes/modules/` (يُحمّل تلقائياً) |
| مسارات API | `routes/api.php` أو ملف في `routes/modules/api/` |

## الأدلة التفصيلية (بالإنجليزية)

| الدليل | متى تقرؤه |
|---|---|
| [Getting started](docs/getting-started.md) | عند بدء مشروع جديد من القالب، وفيه قائمة **ما لا يجب حذفه** |
| [Architecture](docs/architecture.md) | لتعرف مكان كل شيء ولماذا |
| [Modules](docs/modules.md) | عند إضافة قسم، بـ `make:module` أو يدوياً |
| [Permissions](docs/permissions.md) | عند حماية صفحة أو زر أو مسار API |
| [Settings](docs/settings.md) | عند الحاجة لقيمة يغيرها المدير من المتصفح |
| [Activity log & notifications](docs/activity-and-notifications.md) | لتسجيل ما حدث أو لتنبيه شخص |
| [Translations](docs/translations.md) | عند إضافة نص أو لغة جديدة، وقواعد الاتجاه من اليمين لليسار |
| [Deployment](docs/deployment.md) | عند النشر على Railway أو أي خادم Docker أو خادم عادي |
| [Security](docs/security.md) | لتعرف ما هو محمي مسبقاً وما يبقى عليك |

التعليمات موجودة **داخل الكود** أيضاً: كل ملف إعداد يُتوقع منك تعديله في `config/` (`permission_tree.php` و`settings.php` و`menu.php`) يبدأ بشرح لطريقة كتابته، والفئات الأساسية في `app/Support` تشرح طريقة استخدامها في أعلى الملف.
وملف [AGENTS.md](AGENTS.md) فيه القواعد لمساعدي البرمجة بالذكاء الاصطناعي.

## الاختبارات والفحوصات

</div>

```bash
php artisan test                 # 114 اختباراً
php artisan lang:missing ar      # كل نص إنجليزي له ترجمة عربية
php artisan permissions:check    # كل صلاحية مستخدمة في الكود موجودة في الشجرة
vendor/bin/pint --test           # تنسيق الكود
```

<div dir="rtl">

الأربعة تعمل تلقائياً مع كل رفع في GitHub Actions (`.github/workflows/tests.yml`)، ومعها بناء صورة Docker الخاصة بالإنتاج.

## الترخيص

MIT، انظر [LICENSE](LICENSE). كُتب هذا القالب من الصفر ولا يحتوي على أي كود من Snipe-IT (المرخص بـ AGPL)، فالمشاريع المبنية عليه تختار أي ترخيص تريده.

</div>
