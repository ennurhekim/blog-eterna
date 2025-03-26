<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // İlk kullanıcıyı al (Eğer yoksa UserSeeder çalıştır)
        $user = User::first();

        if (!$user) {
            $this->command->info('Önce bir kullanıcı oluşturmalısınız.');
            return;
        }

        // Blog verileri
        $blogs = [
            [
                'title' => 'Laravel ile Blog Yönetimi',
                'content' => 'Laravel, PHP tabanlı güçlü bir framework olup, blog oluşturma işlemini oldukça kolaylaştırır. Bu yazımızda, Laravel kullanarak sıfırdan bir blog sistemi kurmak için adım adım yapmanız gerekenleri anlatacağız. Öncelikle, Laravel’in kurulumunu yaparak projeye başlıyoruz. Ardından, blog yazıları eklemek, kategoriler oluşturmak ve her yazıya ait slug oluşturmak gibi önemli adımları takip ediyoruz. Laravel ile blog oluşturmak, hem esnekliği hem de kullanımı kolay yapısıyla her geliştirici için cazip bir seçenek sunar. Ayrıca, Laravel’in sunduğu Blade şablon motoru sayesinde, şablonlar üzerinde hızlıca değişiklik yaparak blog sayfalarını kişiselleştirebilirsiniz.',
                'cover_image' => 'front/1.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'PHP 8 Yenilikleri',
                'content' => 'PHP 8, PHP dünyasında büyük bir yenilik olarak karşımıza çıkıyor. Yeni sürümle birlikte birçok geliştirme yapıldı. Bu yazıda PHP 8 ile gelen yeniliklerden bahsedeceğiz. Öncelikle, PHP 8’in en dikkat çeken özelliklerinden biri olan "JIT (Just-In-Time) compiler" özelliği, performansı önemli ölçüde artırıyor. Ayrıca, PHP 8 ile gelen "Union Types" sayesinde, daha esnek tip kontrolü yapılabiliyor. "Named Arguments" özelliği ise fonksiyonları daha okunabilir hale getiriyor. PHP 8 ayrıca daha önce hatalı olan birçok işlevde de iyileştirmeler sundu. PHP 8’in getirdiği bu yenilikler, PHP geliştiricilerinin işini kolaylaştırıyor ve uygulama geliştirmeyi daha verimli hale getiriyor.',
                'cover_image' => 'front/2.jpg',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Next.js vs Laravel',
                'content' => 'Next.js ve Laravel, web geliştirme dünyasında önemli yer tutan iki popüler framework’tür. Ancak, kullanım amacına göre birbirlerinden farklılıklar gösterirler. Next.js, React tabanlı bir framework olup, sunucu tarafı rendering (SSR) özellikleriyle ön plana çıkar. Bu, SEO açısından önemli avantajlar sağlar. Öte yandan, Laravel PHP tabanlı bir MVC framework’üdür ve backend geliştirme için oldukça güçlüdür. Laravel, veritabanı yönetimi, kullanıcı kimlik doğrulaması, ve API desteği gibi birçok temel özellik sunar. Next.js ise öncelikli olarak frontend geliştirme için uygundur. Her iki framework de güçlüdür, ancak ihtiyacınıza göre seçim yapmak önemlidir. Örneğin, sadece frontend geliştirecekseniz Next.js’i, tam bir uygulama geliştirecekseniz Laravel’i tercih etmelisiniz.',
                'cover_image' => 'front/3.jpg',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Web Geliştirme için En İyi Araçlar',
                'content' => 'Web geliştirme dünyası, sürekli olarak gelişen ve değişen bir alan. Bu yazımızda, web geliştirme sürecinde kullanılan en iyi araçlardan bahsedeceğiz. En başta, HTML, CSS ve JavaScript gibi temel diller yer alıyor. Ancak, modern web geliştirme, bu dillerin yanı sıra birçok başka aracı da içeriyor. Özellikle React, Angular ve Vue.js gibi JavaScript framework’leri, frontend geliştirmede devrim yaratmış durumda. Backend tarafında ise Node.js, Laravel, Django ve Ruby on Rails gibi güçlü framework’ler yer alıyor. Ayrıca, veritabanı yönetimi için MySQL, PostgreSQL ve MongoDB gibi popüler seçenekler bulunuyor. Web geliştirme sürecinde kullanılan bu araçlar, geliştiricilerin işini kolaylaştırır ve daha verimli bir çalışma ortamı sağlar.',
                'cover_image' => 'front/4.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'SEO ve Web Tasarımı',
                'content' => 'SEO (Arama Motoru Optimizasyonu), web tasarımı ile oldukça yakından ilişkilidir. İyi bir web tasarımı, SEO için büyük önem taşır çünkü kullanıcı deneyimini doğrudan etkiler. Arama motorları, kullanıcıların en iyi deneyimi aldığı siteleri ödüllendirir ve bu da sıralamalarda yükselmenize yardımcı olur. SEO dostu bir tasarım, sayfa yükleme hızını artırmak, mobil uyumlu olmak ve düzgün bir navigasyon sunmak gibi unsurları içerir. Ayrıca, doğru anahtar kelimelerle optimizasyon yaparak, arama motorlarında daha iyi bir sıralama elde edebilirsiniz. SEO, sadece teknik bir işlem değil, aynı zamanda görsel ve kullanıcı odaklı bir tasarım sürecidir.',
                'cover_image' => 'front/5.jpg',
                'published_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Veritabanı Tasarımı ve Yönetimi',
                'content' => 'Veritabanı tasarımı, web uygulamalarının temel yapı taşlarından biridir. İyi bir veritabanı tasarımı, uygulamanızın verimli çalışmasını sağlar ve veri yönetimini kolaylaştırır. Veritabanı tasarımında dikkat edilmesi gereken en önemli unsurlardan biri, normalizasyon işlemidir. Normalizasyon, verilerin tekrarlanmasını engelleyerek, veritabanının daha düzenli ve verimli hale gelmesini sağlar. Ayrıca, doğru ilişkilendirmeler yapmak, veri bütünlüğünü korumak ve performansı optimize etmek de önemli faktörlerdir. Veritabanı yönetim sistemleri (DBMS) kullanarak, verilerinizi güvenli bir şekilde depolayabilir ve erişebilirsiniz. MySQL, PostgreSQL, Oracle ve MongoDB gibi veritabanı yönetim sistemleri, farklı ihtiyaçlara yönelik çözümler sunar.',
                'cover_image' => 'front/6.jpg',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'title' => 'Web Güvenliği ve En İyi Uygulamalar',
                'content' => 'Web güvenliği, her web uygulaması için kritik bir öneme sahiptir. Güvenlik açıkları, hem kullanıcı verilerini tehlikeye atabilir hem de uygulamanın işleyişini bozabilir. Bu yazımızda, web güvenliği konusunda dikkat edilmesi gereken en önemli uygulamalara değineceğiz. İlk olarak, HTTPS kullanımı, web güvenliği için temel bir adımdır. SSL sertifikası kullanarak, tüm iletişimin şifrelenmesini sağlarsınız. Ayrıca, SQL injection gibi yaygın saldırılara karşı önlem almalı ve veritabanı sorgularını güvenli hale getirmelisiniz. CSRF ve XSS saldırılarına karşı da uygun güvenlik önlemleri almak, web uygulamanızın güvenliğini artıracaktır. Web güvenliği, sürekli olarak gelişen bir alan olduğu için, güncel tehditlere karşı önlemler almak önemlidir.',
                'cover_image' => 'front/7.jpg',
                'published_at' => Carbon::now(),
            ],
            [
                'title' => 'Kullanıcı Deneyimi (UX) Tasarımı',
                'content' => 'Kullanıcı Deneyimi (UX) tasarımı, web ve mobil uygulamalarının başarısında önemli bir rol oynar. UX tasarımı, kullanıcıların bir uygulamayı kullanırken yaşadığı deneyimi iyileştirmeyi amaçlar. Kullanıcı dostu bir tasarım, kullanıcıların uygulamayı daha rahat ve verimli bir şekilde kullanmalarını sağlar. İyi bir UX tasarımı için, kullanıcıların ihtiyaçlarını anlamak ve bu ihtiyaçlara uygun çözümler sunmak gereklidir. Ayrıca, kullanıcıların kolayca gezinebileceği bir arayüz, etkili bir bilgi mimarisi ve hızlı yükleme süreleri de önemli faktörlerdir. Kullanıcı geri bildirimleri alarak, sürekli olarak UX tasarımını geliştirebilirsiniz.',
                'cover_image' => 'front/8.jpg',
                'published_at' => Carbon::now()->subDays(5),
            ],
        ];
        // Blogları oluştur
        foreach ($blogs as $blog) {
            $blogData = Blog::create([
                'title' => $blog['title'],
                'content' => $blog['content'],
                'published_at' => $blog['published_at'],
                'user_id' => $user->id, // İlk kullanıcıyı blog yazarı olarak kullanıyoruz
            ]);

            // Dosyanın public/front dizininde olup olmadığını kontrol et
            $imagePath = public_path($blog['cover_image']);
            if (file_exists($imagePath)) {
                // Media Library'e sadece dosya yolunu ekleyerek, kopyalamadan ilişkilendiriyoruz.
                $blogData->addMedia($imagePath)
                    ->preservingOriginal()  // Bu fonksiyon, dosyanın orijinalini saklar ve silmez.
                    ->toMediaCollection('cover_images'); // cover_images koleksiyonuna ekliyoruz
            }
        }
    }
}
