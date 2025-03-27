<template>
  <form @submit.prevent="handleRegister">
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Kayıt Formu</h2>
        
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input id="email" v-model="form.email"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your email" />
          <span v-if="form.errors.email" class="text-red-500 text-xs">
            {{ form.errors.email }}
          </span>
        </div>

        <!-- Name -->
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">İsminizi giriniz</label>
          <input id="name" v-model="form.name"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your first name" />
          <span v-if="form.errors.name" class="text-red-500 text-xs">
            {{ form.errors.name }}
          </span>
        </div>

        <!-- Surname -->
        <div class="mb-4">
          <label for="surname" class="block text-sm font-medium text-gray-700">Soy isminizi giriniz</label>
          <input id="surname" v-model="form.surname"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your last name" />
          <span v-if="form.errors.surname" class="text-red-500 text-xs">
            {{ form.errors.surname }}
          </span>
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label for="phone" class="block text-sm font-medium text-gray-700">Telefon numaranızı giriniz</label>
          <input id="phone" v-model="form.phone"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your phone" />
          <span v-if="form.errors.phone" class="text-red-500 text-xs">
            {{ form.errors.phone }}
          </span>
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Şifrenizi giriniz</label>
          <input id="password" type="password" v-model="form.password"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Enter your password" />
          <span v-if="form.errors.password" class="text-red-500 text-xs">
            {{ form.errors.password }}
          </span>
        </div>

        <!-- Password Confirmation -->
        <div class="mb-4">
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Şifrenizi tekrar giriniz</label>
          <input id="password_confirmation" type="password" v-model="form.password_confirmation"
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Confirm your password" />
          <span v-if="form.errors.password_confirmation" class="text-red-500 text-xs">
            {{ form.errors.password_confirmation }}
          </span>
        </div>

        <button
          class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 cursor-pointer">
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

<script lang="ts">
import axios from "axios";
import { reactive } from "vue";

export default {
  data() {
    return {
      form: reactive({
        email: "",
        name: "",
        surname: "",
        phone: "",
        password: "",
        password_confirmation: "",
        errors: {
          email: "",
          name: "",
          surname: "",
          phone: "",
          password: "",
          password_confirmation: "",
        },
      }),
    };
  },
  methods: {
    async handleRegister() {
      // Form geçerliliğini kontrol et
      if (this.validateForm()) {
        try {
          const response = await axios.post(`${this.$apiBaseURL}/register`, {
            email: this.form.email,
            name: this.form.name,
            surname: this.form.surname,
            phone: this.form.phone,
            password: this.form.password,
            password_confirmation: this.form.password_confirmation,
          });

          if (response.data.success) {
            alert("Kayıt başarılı");
            window.location.href = "/login";
          } else {
            alert("Kayıt başarısız!");
          }
        } catch (error) {
          console.error("İstek başarısız:", error);
        }
      }
    },
    validateForm() {
      let isValid = true;
      this.form.errors = { email: "", name: "", surname: "", phone: "", password: "", password_confirmation: "" };

      if (!this.form.email) {
        this.form.errors.email = "Email alanı zorunlu";
        isValid = false;
      }

      if (!this.form.name) {
        this.form.errors.name = "İsim alanı zorunlu";
        isValid = false;
      }

      if (!this.form.surname) {
        this.form.errors.surname = "Soyisim alanı zorunlu";
        isValid = false;
      }

      if (!this.form.phone) {
        this.form.errors.phone = "Telefon alanı zorunlu";
        isValid = false;
      }

      if (!this.form.password) {
        this.form.errors.password = "Şifre alanı zorunlu";
        isValid = false;
      }

      if (!this.form.password_confirmation) {
        this.form.errors.password_confirmation = "Şifre tekrar alanı zorunlu";
        isValid = false;
      }

      if (this.form.password !== this.form.password_confirmation) {
        this.form.errors.password_confirmation = "Şifreler uyuşmuyor";
        isValid = false;
      }

      return isValid;
    },
  },
};
</script>

<style scoped>
/* Burada stilleri ekleyebilirsiniz */
</style>
