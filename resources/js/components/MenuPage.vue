<template>
  <div class="container px-4 md:px-0 max-w-6xl mx-auto -mt-32">
    <div class="mx-0 sm:mx-6">
      <!-- Navbar -->
      <nav class="w-full bg-gradient-to-r from-gray-800 to-gray-900 shadow-lg rounded-lg p-4">
        <div class="container mx-auto flex items-center justify-between">
          <!-- Sol Menü -->
          <div class="flex items-center space-x-6">
            <a class="text-gray-200 font-semibold hover:text-blue-400 transition" href="/">Anasayfa</a>

            <!-- Dinamik Kategoriler -->
            <div v-for="category in categories" :key="category.id" 
              class="relative group" 
              @mouseenter="category.isHovered = true" 
              @mouseleave="category.isHovered = false">
              
              <!-- Ana kategoriye tıklanabilir link -->
              <a @click="goToCategory(category.slug)" 
                class="text-gray-200 font-semibold hover:text-blue-400 transition focus:outline-none cursor-pointer">
                {{ category.name }}
              </a>

              <!-- Alt Kategoriler -->
              <div v-if="category.isHovered && category.children.length"
                class="absolute left-0 w-48 bg-white text-gray-800 shadow-lg rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity z-20"
                @mouseenter="category.isHovered = true" 
                @mouseleave="category.isHovered = false">
                <ul class="py-2">
                  <li v-for="sub in category.children" :key="sub.id">
                    <a @click="goToCategory(sub.slug)" class="block px-4 py-2 hover:bg-gray-200 cursor-pointer">
                      {{ sub.name }}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Sağ Menü -->
          <div class="flex items-center space-x-4">
            <!-- Giriş yapılmamışsa Login ve Register butonları görünür -->
            <template v-if="!isLoggedIn">
              <a class="text-gray-200 font-semibold hover:text-blue-400 transition" href="/login">Giriş Yap</a>
              <a class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition" href="/register">Kayıt Ol</a>
            </template>

            <!-- Giriş yapılmışsa Logout butonu görünür -->
            <a v-if="isLoggedIn" @click="logout"
              class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition cursor-pointer">
              Çıkış Yap
            </a>
          </div>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      isLoggedIn: false,
      categories: []
    };
  },
  async mounted() {
    this.checkLoginStatus();
    await this.fetchCategories();
  },
  methods: {
    checkLoginStatus() {
      this.isLoggedIn = !!localStorage.getItem('auth_token');
    },
    async fetchCategories() {
      try {
        const response = await axios.get(this.$apiBaseURL+'/categories');
        this.categories = response.data.data.categories;
        // Her kategoriye 'isHovered' özelliği ekleyelim
        this.categories.forEach(category => {
          category.isHovered = false;
        });
      } catch (error) {
        console.error('Kategorileri çekerken hata oluştu:', error);
      }
    },
    goToCategory(slug) {
      this.$router.push(`/category/${slug}`);
    },
    logout() {
      localStorage.removeItem('auth_token');
      this.isLoggedIn = false;
      this.$router.push('/login');
    }
  }
};
</script>

<style scoped>
/* Kategori dropdown menüsü için */
.group:hover .group-hover\:opacity-100 {
  opacity: 1;
  visibility: visible;
  transition: opacity 0.3s ease-in-out;
}

/* Alt kategori menüsünün gizlilik durumunu kontrol et */
.group:hover .group-hover\:visible {
  visibility: visible;
}

.relative:hover .group-hover\:visible {
  visibility: visible;
  z-index: 10;
}

/* Kategori alt menülerine ekstra bir z-index ekleyelim */
.group-hover\:visible {
  z-index: 10;
}
</style>
