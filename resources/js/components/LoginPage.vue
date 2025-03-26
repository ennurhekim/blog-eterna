<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
      <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Login</h2>

      <!-- Giriş Formu -->
      <form @submit.prevent="handleLogin">
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" id="email" v-model="form.email.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required placeholder="Enter your email" />
          <span v-if="form.email.$error" class="text-red-500 text-xs">
            {{ form.email.$error.message }}
          </span>
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" v-model="form.password.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required placeholder="Enter your password" />
          <span v-if="form.password.$error" class="text-red-500 text-xs">
            {{ form.password.$error.message }}
          </span>
        </div>
        <p v-if="errorMessage" class="text-red-500 text-xs mb-4">{{ errorMessage }}</p>
        <button type="submit"
          class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50  cursor-pointer">
          Login
        </button>
      </form>

      <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
          Hesabınız yokmu?
          <a href="/register" class="text-blue-600 hover:underline">Kayıt Ol</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineForm, field } from "vue-yup-form";
import * as yup from "yup";
import axios from "axios";
import { ref } from "vue";

const form = defineForm({
  email: field("", yup.string().required("Email alanı zorunlu")),
  password: field("", yup.string().required("Şifre alanı zorunlu")),
});
const errorMessage = ref("");

async function handleLogin() {
  try {
    
    const response = await axios.post("http://localhost:8000/api/login", {
      email: form.email.$value,
      password: form.password.$value,
    });

    if (response.data.success) {
      localStorage.setItem("auth_token", response.data.data.token);
      window.location.href = "/"; // Sayfayı yenileyerek yönlendirme
    } else {
      errorMessage.value = response.data.message || "Bir hata oluştu.";
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || "Giriş başarısız!";
  }
}
</script>
<style scoped></style>
