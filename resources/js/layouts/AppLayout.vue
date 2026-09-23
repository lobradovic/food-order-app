<script setup>
import { Link,router,usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
defineProps({ title: { type: String, default: '' } })


const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.role?.naziv_role === 'Admin');
function logout() {
  router.post('/logout')
}

function redirectPavle()
{
  window.open('https://www.restoranpavle.com', '_blank')
}
</script>

<template>
  <div class="template page-flex">
    <div class="topbar">
      <span>Restoran Pavle — admin panel</span>
      <span>{{ new Date().toLocaleDateString('sr-RS') }}</span>
    </div>

    <header class="navbar">
      <div>
        <div class="logo-main"><img src="images/logo-black.png" alt="Logo"></div>
      </div>
      <nav class="nav-links">
      
        <template v-if="isAdmin">
          <Link href="/rezervacija">Rezervacije</Link>
          <Link href="/jelo">Jela</Link>
          <Link href="/dnevna-ponuda">Dnevne ponude</Link>
        </template>
        <Link href="/">Meni</Link>
        <!--<Link href="/korpa">Korpa</Link>--> <!--POSTAVLJENO SADA KAO MODAL, ne koristiti ovo vise-->

        <Link href="/moje-porudzbine">Moje porudžbine</Link>
        <Link href="#"  @click="logout()">Odjava</Link>
      </nav>
    </header>

    <div class="hero">
      <h1>{{ title }}</h1>
    </div>

    <main class="page-content">
      <slot />
    </main>

    <footer class="site-footer">
      <div class="footer-main">
        <div class="foot-col">
          <nav class="nav-links">
            <Link href="#" @click="redirectPavle">Glavni sajt</Link> <!--srediti-->
            <template v-if="isAdmin">
              <Link href="/rezervacija">Rezervacije</Link>
              <Link href="/jelo">Jela</Link>
              <Link href="/dnevna-ponuda">Dnevne ponude</Link>
            </template>
            <Link href="/">Meni</Link>
            <!--<Link href="/korpa">Korpa</Link>--> <!--POSTAVLJENO SADA KAO MODAL, ne koristiti ovo vise-->

            <Link href="/moje-porudzbine">Moje porudžbine</Link>
            <Link href="#"  @click="logout()">Odjava</Link>
          </nav>
        </div>
        <div class="foot-col">

        </div>
      </div>

      © {{ new Date().getFullYear() }} Restoran Pavle
    </footer>
  </div>
</template>