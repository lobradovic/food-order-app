<script setup>

import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useCartStore } from '@/stores/cart'
import { store as rezervacijaStore } from '@/actions/App/Http/Controllers/RezervacijaController'

const cart = useCartStore()

const SVI_TERMINI = [9, 10, 11, 12, 13, 14]

const dostupniTermini = computed(() => {
  const sada = new Date()
  return SVI_TERMINI.filter((t) => {
    const krajnjiRok = new Date()
    krajnjiRok.setHours(t - 1, 0, 0, 0)
    return sada < krajnjiRok
  })
})

const form = ref({ termin: '', adresa: '', napomena: '' })
const errors = ref({})
const processing = ref(false)

function posalji() {
  processing.value = true
  const action = rezervacijaStore()

  router.visit(action.url, {
    method: action.method,
    data: {
      termin: form.value.termin,
      adresa: form.value.adresa,
      napomena: form.value.napomena,
      stavke: cart.items.map((i) => ({ jelo_id: i.jeloId, kolicina: i.kolicina })),
    },
    onSuccess: () => {
      cart.isprazni()
      processing.value = false
    },
    onError: (e) => {
      errors.value = e
      processing.value = false
    },
  })
}
</script>

<template>
  <div v-if="cart.items.length === 0">
    <p>Korpa je prazna.</p>
  </div>

  <div v-else>
    <table class="table razdvoj">
      <thead>
        <tr>
          <th>Jelo</th>
          <th>Cena</th>
          <th>Količina</th>
          <th>Ukupno</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in cart.items" :key="item.jeloId">
          <td>{{ item.nazivJela }}</td>
          <td class="price-cell">{{ item.cena }} RSD</td>
          <td>
            <input
              type="number"
              min="1"
              :value="item.kolicina"
              @change="cart.setKolicina(item.jeloId, +$event.target.value)"
              style="width: 4em;"
            />
          </td>
          <td class="price-cell">{{ item.cena * item.kolicina }} RSD</td>
          <td><button class="dugme" @click="cart.ukloni(item.jeloId)">Ukloni</button></td>
        </tr>
      </tbody>
    </table>

    <p class="razdvoj"><strong>Ukupno: <span class="price-cell">{{ cart.ukupnaCena }} RSD</span></strong></p>
    </div>

<h2 class="section-heading">Podaci za dostavu</h2>
  <form @submit.prevent="posalji">
    <div class="razdvoj">
      <label>Termin isporuke</label><br>
      <select v-model="form.termin">
        <option value="" disabled>Izaberi termin</option>
        <option v-for="t in dostupniTermini" :key="t" :value="t">{{ t }}:00</option>
      </select>
      <div v-if="dostupniTermini.length === 0" style="color:var(--maroon)">
        Nema više dostupnih termina za danas.
      </div>
      <div v-if="errors.termin" style="color:var(--maroon)">{{ errors.termin }}</div>
    </div>

    <div class="razdvoj">
      <label>Adresa</label><br>
      <input type="text" v-model="form.adresa" style="width: 100%;" />
      <div v-if="errors.adresa" style="color:var(--maroon)">{{ errors.adresa }}</div>
    </div>
    <div class="razdvoj">
  <label>Napomena (opciono)</label><br>
    <textarea v-model="form.napomena" rows="3" style="width: 100%;"></textarea>
  <div v-if="errors.napomena" style="color:var(--maroon)">{{ errors.napomena }}</div>
  </div>
    <button type="submit" class="dugme" :disabled="processing || dostupniTermini.length === 0">
      Pošalji porudžbinu
    </button>
  </form>
</template>