<template>
  <form @submit.prevent="handleRegister">
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Kayıt Formu</h2>
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input id="email" v-model="form.email.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your first name" />
          <span v-if="form.email.$error" class="text-red-500 text-xs">
            {{ form.email.$error.message }}
          </span>
        </div>
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">İsminizi giriniz</label>
          <input id="name" v-model="form.name.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your first name" />
          <span v-if="form.name.$error" class="text-red-500 text-xs">
            {{ form.name.$error.message }}
          </span>
        </div>
        <div class="mb-4">
          <label for="surname" class="block text-sm font-medium text-gray-700">Soy isminizi giriniz</label>
          <input id="surname" v-model="form.surname.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your last name" />
          <span v-if="form.surname.$error" class="text-red-500 text-xs">
            {{ form.surname.$error.message }}
          </span>
        </div>
        <div class="mb-4">
          <label for="phone" class="block text-sm font-medium text-gray-700">Telefon numaranızı giriniz</label>
          <input id="phone" v-model="form.phone.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your last name" />
          <span v-if="form.phone.$error" class="text-red-500 text-xs">
            {{ form.phone.$error.message }}
          </span>
        </div>
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Şifrenizi giriniz</label>
          <input id="password" v-model="form.password.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your last name" />
          <span v-if="form.password.$error" class="text-red-500 text-xs">
            {{ form.password.$error.message }}
          </span>
        </div>

        <div class="mb-4">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Şifrenizi giriniz</label>
          <input id="password_confirmation" v-model="form.password_confirmation.$value"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your last name" />
          <span v-if="form.password_confirmation.$error" class="text-red-500 text-xs">
            {{ form.password_confirmation.$error.message }}
          </span>
        </div>
        <button
          class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50  cursor-pointer">
          Submit
        </button>
        <div class="mt-4 text-center">
          <p class="text-sm text-gray-600">
            Zaten bir hesabınız varmı?
            <a href="/login" class="text-blue-600 hover:underline">Giriş Yap</a>
          </p>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup lang="ts">
import { defineForm, field, isValidForm, toObject } from "vue-yup-form";
import * as yup from "yup";
import axios from "axios";
// Form validasyonu
const form = defineForm({
  email: field("", yup.string().required("Email alanı zorunlu")),
  name: field("", yup.string().required("İsim alanı zorunlu")),
  surname: field("", yup.string().required("Soyisim alanı zorunlu")),
  phone: field("", yup.string().required("Telefon alanı zorunlu")),
  password: field("", yup.string().required("Şifre alanı zorunlu")),
  password_confirmation: field("", yup.string().required("Şifre tekrar alanı zorunlu")),
});
async function handleRegister() {
  if (!isValidForm(form)) {
    alert("iss");
    return;
  }
  try {
    const response = await axios.post("http://localhost:8000/api/register", {
      email: form.email.$value,
      name: form.name.$value,
      surname: form.surname.$value,
      phone: form.phone.$value,
      password: form.password.$value,
      password_confirmation: form.password_confirmation.$value,
    });

    if (response.data.success) {
      alert("Kayıt başarılı");
      window.location.href = "/login";
    } else {
      console.log("Kayıt başarısız!");
    }
  } catch (error) {
    console.error("İstek başarısız:", error);
  }
}

</script>
