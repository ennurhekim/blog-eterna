
# Laravel Blog Projesi Kurulumu

## Ortam Dosyası Ayarları

`.env.example` dosyasını `.env` olarak değiştirin ve Site Url, veritabanı ile mail bilgilerini girin.

```sh
Örnek
APP_URL=https://blog-eterna.softrede.com
VITE_API_BASE_URL=https://blog-eterna.softrede.com

```

---

## Vue Js ayarları

`app.js` dosyasındaki ` app.config.globalProperties.$apiBaseURL = "https://blog-eterna.softrede.com/api" ;`  site url kısmını degiştir.

---
## Veritabanı Seed İşlemleri

Tüm yapıyı eklemek için (Admin, Users, Writer, vb.):

```sh
php artisan db:seed
```

Belirli bir Seeder eklemek için:

```sh
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=UsersSeeder
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=BlogSeeder
```

---

## Public Klasörüne Storage Bağlantısı

Resim dosyalarını public klasörüne bağlamak için aşağıdaki komutu çalıştırın:

```sh
php artisan storage:link
```

---

## Queue İşlemleri

Queue işlemlerini çalıştırmak için aşağıdaki komutu kullanın:

```sh
php artisan queue:work
```

---

## Schedule İşlemleri

Belirli işlemleri belirli zamanlarda çalıştırmak için aşağıdaki komutu kullanın (örneğin, yorum yapılmamış blog yazılarını silme işlemi gibi):

```sh
php artisan schedule:work
```

---

## Yorum Onayı

Yorumlar, yönetici tarafından onaylanabilir, onaylanmayabilir veya beklemeye alınabilir.

---

## Log Kayıtları

Log kayıtlarını görmek için aşağıdaki URL'yi ziyaret edebilirsiniz:

```
http://localhost:8000/log-viewer
```

---

## Test Kullanıcı Bilgileri

**Admin:**

- Email: ennur2828@gmail.com
- Phone: +905350435140
- Password: 12345678

**Writer:**

- Email: writer@test.com
- Phone: +905350435141
- Password: 12345678

**User:**

- Email: user@test.com
- Phone: +905350435142
- Password: 12345678
