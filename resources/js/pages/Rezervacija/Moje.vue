<script setup>
import { ref } from 'vue'
import Modal from '@/components/Modal.vue'

defineProps({ rezervacije: Array, title: String })

const showDetalji = ref(false)
const viewing = ref(null)

function otvoriDetalje(rezervacija) {
  viewing.value = rezervacija
  showDetalji.value = true
}

function zatvoriDetalje() {
  showDetalji.value = false
}

function ukupno(rezervacija) {
  return rezervacija.stavkas.reduce((sum, s) => sum + s.trenutna_cena * s.kolicina, 0)
}
</script>

<template>
  <table class="table">
    <thead>
      <tr>
        <th>Datum</th>
        <th>Termin</th>
        <th>Ukupno</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="r in rezervacije" :key="r.id">
        <td>{{ r.datum }}</td>
        <td>{{ r.termin }}:00</td>
        <td class="price-cell">{{ ukupno(r) }} RSD</td>
        <td>{{ r.status?.naziv_statusa }}</td>
        <td><button class="dugme" @click="otvoriDetalje(r)">Detalji</button></td>
      </tr>
    </tbody>
  </table>

  <p v-if="rezervacije.length === 0">Još uvek nemaš porudžbina.</p>

  <Modal :show="showDetalji" @close="zatvoriDetalje">
    <h2 class="section-heading">Porudžbina — {{ viewing?.datum }}, {{ viewing?.termin }}:00</h2>

    <p><strong>Adresa:</strong> {{ viewing?.adresa }}</p>
    <p v-if="viewing?.napomena"><strong>Napomena:</strong> {{ viewing.napomena }}</p>
    <p><strong>Status:</strong> {{ viewing?.status?.naziv_statusa }}</p>

    <h3 style="margin-top: 1em;">Stavke</h3>
    <ul style="list-style: none; padding: 0;">
      <li v-for="s in viewing?.stavkas ?? []" :key="s.id" style="margin-bottom: .5em;">
        {{ s.jelo?.naziv_jela }} × {{ s.kolicina }} —
        <span class="price-cell">{{ s.trenutna_cena * s.kolicina }} RSD</span>
      </li>
    </ul>

    <p class="razdvoj"><strong>Ukupno: <span class="price-cell">{{ viewing ? ukupno(viewing) : 0 }} RSD</span></strong></p>

    <button type="button" class="dugme" @click="zatvoriDetalje">Zatvori</button>
  </Modal>
</template>