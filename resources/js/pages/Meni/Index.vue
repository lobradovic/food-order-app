<script setup>
  import { ref, computed } from 'vue'
  import { useCartStore } from '@/stores/cart'
  import KorpaModal from '@/components/KorpaModal.vue'

  const props = defineProps({ ponuda: Array, title: String })
  const cart = useCartStore()

  const dnevnaJela = computed(() => props.ponuda.filter((j) => j.izvor === 'dnevna'))
  const stalnaJela = computed(() => props.ponuda.filter((j) => j.izvor === 'stalna'))

  const showKorpa = ref(false)
</script>

<template>
  <div v-if="dnevnaJela.length > 0" class="razdvoj">
    <h2 class="section-heading">Dnevna ponuda</h2>
    <div class="menu razdvoj">
      <div class="menuItem" v-for="jelo in dnevnaJela" :key="jelo.id">
        <strong>{{ jelo.naziv_jela }}</strong>
        <p v-if="jelo.opis" style="color:var(--item-text); font-size:.9rem;">{{ jelo.opis }}</p>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <span class="price-cell">{{ jelo.cena }} RSD</span>
          <button class="dugme" @click="cart.dodaj(jelo)">Dodaj u korpu</button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <h2 class="section-heading">Stalna ponuda</h2>
    <div class="menu">
      <div class="menuItem" v-for="jelo in stalnaJela" :key="jelo.id">
        <strong>{{ jelo.naziv_jela }}</strong>
        <p v-if="jelo.opis" style="color:var(--item-text); font-size:.9rem;">{{ jelo.opis }}</p>
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <span class="price-cell">{{ jelo.cena }} RSD</span>
          <button class="dugme" @click="cart.dodaj(jelo)">Dodaj u korpu</button>
        </div>
      </div>
    </div>
  </div>
    <button v-if="cart.brojStavki > 0" class="dugme sticky-cart-btn" @click="showKorpa = true">Završi porudžbinu ({{ cart.brojStavki }}) — {{ cart.ukupnaCena }} RSD</button>
    <KorpaModal :show="showKorpa" @close="showKorpa = false" />
</template>