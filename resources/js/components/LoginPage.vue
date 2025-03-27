<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
      <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login</h2>

      <!-- Giriş Formu -->
      <form @submit.prevent="handleLogin">
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email Yada Telefon</label>
          <input type="text" id="email" ref="email"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required placeholder="Enter your email" />
       
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" ref="password"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required placeholder="Enter your password" />
       
        </div>

        <p v-if="errorMessage" class="text-red-500 text-xs mb-4">{{ errorMessage }}</p>

        <button type="submit"
          class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 cursor-pointer">
          Login
        </button>
      </form>

      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
          Hesabınız yok mu?
          <a href="/register" class="text-blue-600 hover:underline">Kayıt Ol</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import { ref } from "vue";

export default {
  data() {
    return {
      errorMessage: ref(""),
    };
  },
  methods: {
    async handleLogin() {
      try {
        // $refs ile form elemanlarına erişim sağla
        const email = this.$refs.email.value;
        const password = this.$refs.password.value;
        
        const response = await axios.post(this.$apiBaseURL + "/login", {
          email: email,
          password: password,
        });

        if (response.data.success) {
          localStorage.setItem("auth_token", response.data.data.token);
          window.location.href = "/"; 
        } else {
          this.errorMessage = response.data.message || "Bir hata oluştu.";
        }
      } catch (error) {
        this.errorMessage = error.response?.data?.message || "Giriş başarısız!";
      }
    },
  },
};
</script>
