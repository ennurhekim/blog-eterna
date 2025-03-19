# Laravel Proje Kurulumu

## Ortam Dosyası Ayarları

`.env.example` dosyasını `.env` olarak değiştirin ve veritabanı ile mail bilgilerini girin.

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

Resim ve dosya erişimi için `public` klasörüne bağlantı oluşturun:

```sh
php artisan storage:link
```

---

## Test Kullanıcı Bilgileri

### **Admin**

- **E-posta:** [ennur2828@gmail.com](mailto\:ennur2828@gmail.com)
- **Telefon:** +90 535 043 5140
- **Şifre:** 12345678

### **Yazar (Writer)**

- **E-posta:** [writer@test.com](mailto\:writer@test.com)
- **Telefon:** +90 535 043 5141
- **Şifre:** 12345678

### **Kullanıcı (User)**

- **E-posta:** [user@test.com](mailto\:user@test.com)
- **Telefon:** +90 535 043 5142
- **Şifre:** 12345678

