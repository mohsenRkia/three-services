1️Delivery                    ← بیرونی‌ترین (ورودی از کاربر، API)
2️Application                 ← هماهنگ‌کننده‌ی Use-caseها
3️Domain                      ← هسته‌ی قوانین کسب‌وکار
4️Infrastructure              ← ابزار و تکنولوژی (DB، کش، ایمیل و ...)
--------------------------------------------------------------------------
| لایه                 | Responsibility ?                                 | Depend On ?                  | Independent On ?         |
|----------------------|--------------------------------------------------|------------------------------|--------------------------|
| 1️**Delivery**       | ورودی/خروجی رو مدیریت می‌کنه (HTTP, CLI, gRPC)   | Application Layer            | Domain, Infra            |
| 2️**Application**    | اجرای use-caseها (ثبت کاربر، خرید بلیط، ...)     | Domain + Repository Interface | GORM، DB                 |
| 3️**Domain**         | منطق خالص کسب‌وکار (Entity, VO, Domain Service)  | -                            | DB، Web، external tools  |
| 4️**Infrastructure** | پیاده‌سازی DB, Redis, Email, etc                 | Domain Interface             | خودش interface نمی‌سازه! |
--------------------------------------------------------------------------
[ 1. Delivery ]
↓
[ 2. Application Layer ]
↓
[ 3. Domain Layer ]
↑
[ 4. Infrastructure Layer ]
--------------------------------------------------------------------------
| لایه               | چه کاری می‌کنه؟                  | کد نمونه                    |
| ------------------ | -------------------------------- | --------------------------- |
| **Interfaces**     | از HTTP درخواست می‌گیره          | `POST /register`            |
| **Application**    | use-case رو اجرا می‌کنه          | `RegisterUser(email, pass)` |
| **Domain**         | کاربر رو validate و ایجاد می‌کنه | `NewUser(email, pass)`      |
| **Infrastructure** | توی DB ذخیره می‌کنه              | `gorm.Create(&UserModel)`   |
