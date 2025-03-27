<template>
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-center my-8">{{ post.title }}</h1>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <img :src="this.image || '/front/default.jpg'" class="w-full h-64 object-cover" />
            <div class="p-6">
                <p class="text-gray-600 text-xs md:text-sm">{{ formattedCategories(post) }}</p>
                <h2 class="text-xl font-bold text-gray-900 mt-4">{{ post.title }}</h2>
                <p class="text-gray-800 font-serif text-base mt-2">
                    {{ post.content }}
                </p>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-100">
                <div class="flex items-center">
                    <img class="w-8 h-8 rounded-full mr-4" :src="post.user?.avatar || 'http://i.pravatar.cc/300'"
                        alt="Avatar" />
                    <p class="text-gray-600 text-sm">{{ post.user?.name || 'Bilinmeyen Kullanıcı' }}</p>
                </div>
                <p class="text-gray-600 text-xs">{{ formatDate(post.created_at) }}</p>
            </div>
        </div>

        <!-- Yorumlar Bölümü -->
        <div v-if="comments.length > 0" class="mt-8">
            <h3 class="text-2xl font-semibold mb-4">Yorumlar</h3>
            <div v-for="comment in comments" :key="comment.id" class="bg-gray-50 p-4 rounded-lg shadow mb-4">
                <div class="flex items-center mb-2">
                    <img class="w-8 h-8 rounded-full mr-4" :src="comment.user?.avatar || 'http://i.pravatar.cc/300'" />
                    <p class="text-gray-700 font-semibold">{{ comment.user?.name || 'Bilinmeyen Kullanıcı' }}</p>
                    <p class="text-gray-500 text-xs ml-2">{{ formatDate(comment.created_at) }}</p>
                </div>
                <p class="text-gray-700">{{ comment.content }}</p>
            </div>
        </div>

        <!-- Yorum Eklemek İçin Form -->
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-semibold mb-4">Yorum Yap</h3>
            <div v-if="!isAuthenticated" class="text-red-500">Yorum yapabilmek için giriş yapmalısınız.</div>
            <div v-else>
                <textarea v-model="newComment" placeholder="Yorumunuzu buraya yazın..."
                    class="w-full p-4 border border-gray-300 rounded-md mb-4 bg-white"></textarea>
                <button @click="submitComment" class="bg-blue-500 text-white px-6 py-2 rounded-md  cursor-pointer">Yorum
                    Gönder</button>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            post: {},
            image: {},
            comments: [],
            newComment: "",
            authToken: localStorage.getItem("auth_token"), // Kullanıcı giriş yapmış mı kontrol et
            isAuthenticated: !!localStorage.getItem("auth_token"), // Boolean olarak tut
        };
    },
    async created() {
        const slug = this.$route.params.slug;
        await this.fetchPost(slug);
        await this.fetchComments(slug);
    },
    methods: {
        async fetchPost(slug) {
            try {
                const response = await axios.get(this.$apiBaseURL+`/blogs/${slug}`);
                this.post = response?.data?.data?.data;
                this.image = response?.data?.data?.image;
                console.log(response?.data?.data);
                if (this.image && this.image.url) {
                    this.post.image = this.image.url;
                }
            } catch (error) {
                console.error("Veri çekme hatası:", error);
            }
        },
        async fetchComments(slug) {
            try {
                const response = await axios.get(this.$apiBaseURL+`/blogs/${slug}/comments`);
                this.comments = response?.data?.data.comments || [];
            } catch (error) {
                console.error("Yorumları çekme hatası:", error);
            }
        },
        async submitComment() {
            if (!this.isAuthenticated) {
                alert("Lütfen önce giriş yapınız.");
                return;
            }
            if (!this.newComment.trim()) {
                alert("Yorum alanı boş olamaz!");
                return;
            }
            try {
                
                const response = await axios.post(
                    this.$apiBaseURL+`/blogs/${this.post.slug}/comments`,
                    { content: this.newComment },
                    { headers: { Authorization: `Bearer ${this.authToken}` } }
                );
                this.comments.push(response.data.data.comment);
                this.newComment = "";
            } catch (error) {
                console.error("Yorum gönderme hatası:", error);
            }
        },
        formatDate(dateString) {
            const options = { year: "numeric", month: "long", day: "numeric" };
            return new Date(dateString).toLocaleDateString("tr-TR", options);
        },
        formattedCategories(post) {
            return post.categories ? post.categories.map((cat) => cat.name).join(", ") : "Genel";
        },
    },
};
</script>

<style scoped>
/* Stil eklemelerini buraya yapabilirsiniz */
</style>
