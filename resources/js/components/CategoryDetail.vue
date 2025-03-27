<template>
    <div class="container px-4 md:px-0 max-w-6xl mx-auto mt-32">
        <div class="mx-0 sm:mx-6">
            <!-- Kategori Başlık -->
            <h1 class="text-3xl font-semibold text-gray-800 mb-6">{{ category?.name || 'Yükleniyor...' }}</h1>

            <div v-if="blogs.length" class="space-y-8">
                <div v-for="blog in blogs" :key="blog.id" class="bg-white shadow-md rounded-lg p-6 flex">
                    <!-- Sol tarafta kare resim -->
                    <div class="w-32 h-32 bg-gray-200 rounded-md overflow-hidden mr-6">
                        <img :src="blog.image_url || 'default-image-url.jpg'" alt="Blog Görseli"
                            class="w-full h-full object-cover" />
                    </div>

                    <!-- Blog içeriği -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold text-gray-800">{{ blog.title }}</h2>
                        <p class="text-gray-600 mt-2">{{ blog.excerpt }}</p>
                        <!-- Blog içeriği belli karaktere kadar gösterilecek -->
                        <p class="mt-4 text-gray-700">{{ truncateText(blog.content, 200) }}</p>
                        
                        <!-- Devamını Oku Butonu -->
                        <div class="flex justify-start w-full mt-4">
                            <a :href="`/blog/${blog.slug}`" class="devam-butonu">
                                Devamını Oku
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-600">
                <p>Bu kategoride henüz blog yazısı bulunmamaktadır.</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            category: {},  // Kategori verisi
            blogs: [],      // Bloglar listesi
        };
    },
    async mounted() {
        await this.fetchCategory();  // Kategori verisini çek
        await this.fetchBlogs();     // Blog verisini çek
    },
    methods: {
        async fetchCategory() {
            const slug = this.$route.params.slug;
            try {
                const response = await axios.get(this.$apiBaseURL+`/category/${slug}`);
                if (response.data.success) {
                    this.category = response.data.data.category;
                } else {
                    console.error("Kategori bulunamadı.");
                }
            } catch (error) {
                console.error("Kategori alınırken hata oluştu:", error);
            }
        },
        async fetchBlogs() {
            const slug = this.$route.params.slug;
            try {
                const response = await axios.get(this.$apiBaseURL+`/category/${slug}/blogs`);
                if (response.data.success) {
                    this.blogs = response.data.data.blogs;
                } else {
                    console.error("Bloglar alınırken hata oluştu.");
                }
            } catch (error) {
                console.error("Bloglar alınırken hata oluştu:", error);
            }
        },
        truncateText(text, length) {
            if (!text) return "";
            return text.length > length ? text.substring(0, length) + "..." : text;
        }
    },
};
</script>

<style scoped>
.devam-butonu {
    display: inline-block;
    padding: 8px 16px;
    background-color: #3b82f6;
    color: white;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    font-size: 14px;
    text-align: center;
}

.devam-butonu:hover {
    font-weight: bold;
    background-color: #2563eb;
}
</style>
