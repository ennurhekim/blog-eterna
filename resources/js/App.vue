<template>


  <HeaderPage></HeaderPage> <!-- Header bileşenini burada çağırıyoruz -->
  <div class="container px-4 md:px-0 max-w-6xl mx-auto -mt-32">
    <MenuPage></MenuPage>
    <router-view></router-view> <!-- Burada rotanın render edileceği yer -->
  </div>
  <FooterPage></FooterPage> <!-- Footer bileşenini burada çağırıyoruz -->
</template>

<script>
import HeaderPage from './components/HeaderPage.vue';  // Header bileşenini içeri aktar
import FooterPage from './components/FooterPage.vue';  // Footer bileşenini içeri aktar
import MenuPage from './components/MenuPage.vue';  // Footer bileşenini içeri aktar

export default {
  name: 'App',
  components: {
    HeaderPage,  // Header bileşenini kaydet
    FooterPage,   // Footer bileşenini kaydet
    MenuPage,   // Menu bileşenini kaydet
  }
};
</script>
