// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../components/HomePage.vue';
import LoginPage from '../components/LoginPage.vue';
import RegisterPage from '../components/RegisterPage.vue';
import BlogDetail from '../components/BlogDetail.vue';
import CategoryDetail from '../components/CategoryDetail.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomePage,
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterPage, beforeEnter: (to, from, next) => {
      const token = localStorage.getItem('auth_token');
      if (token) {
        next({ name: '/' });  // Kullanıcı giriş yaptıysa ana sayfaya yönlendir
      } else {
        next();
      }
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
    beforeEnter: (to, from, next) => {
      const token = localStorage.getItem('auth_token');
      if (token) {
        next({ name: '/' });  // Kullanıcı giriş yaptıysa ana sayfaya yönlendir
      } else {
        next();
      }
    }
  }, {
    path: '/logout',
    name: 'logout',
    beforeEnter: (to, from, next) => {
      const token = localStorage.getItem('auth_token');
      if (token) {
        localStorage.removeItem('auth_token');
        // Uygulama durumunu güncelleme
        window.location.href = "/login";  // Yönlendirme işlemi
      } else {
        next();
      }
    },
  },
  {
    path: '/blog/:slug', // Dinamik route (slug parametresi alır)
    name: 'blog-detail',
    component: BlogDetail,
    beforeEnter: (to, from, next) => {
      next(); // Route'a devam et
    },
  },
  {
    path: '/category/:slug', // Dinamik route (slug parametresi alır)
    name: 'category-detail',
    component: CategoryDetail,
    beforeEnter: (to, from, next) => {
      next(); // Route'a devam et
    },
  }
  // Diğer rotalar burada tanımlanabilir
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
