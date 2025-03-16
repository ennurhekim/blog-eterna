Geliştirme ortamınızı oluşturmak için şu talimatları izleyin.

Yerel makinenizde geliştirme ortamınızı kurma:

$ git clone https://github.com/ennurhekim/blog-eterna.git
$ cd  blog-eterna
$ cp  .env.example .env
$ php artisan key:generate
$ php artisan migrate
$ php artisan storage:link