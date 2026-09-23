<!-- resources/js/layouts/PublicLayout.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'

defineProps({ title: { type: String, default: '' } })
const cart = useCartStore()
</script>

<template>
  <div>
    <div class="topbar">
      <span>Mike Petrovića Alasa 12-14, Beograd</span>
      <span>+381 69 620 007</span>
      <span>Radno vreme: PON – PET 08-15</span>
    </div>

    <header class="navbar">
      <div>
        <div class="logo-main">P A V L E</div>
        <div class="logo-sub">restoran</div>
      </div>
      <nav class="nav-links">
        <Link href="/">Početna</Link>
        <Link href="/meni">Meni</Link>
        <Link href="/kontakt">Kontakt</Link>
        <Link href="/korpa" style="position: relative;">
          Korpa
          <span v-if="cart.brojStavki > 0" class="cart-badge">{{ cart.brojStavki }}</span>
        </Link>
      </nav>
    </header>

    <div class="hero">
      <h1>{{ title }}</h1>
    </div>

    <main class="page-content">
      <slot />
    </main>

    <footer class="site-footer">
      © {{ new Date().getFullYear() }} Restoran Pavle
    </footer>
  </div>
</template>