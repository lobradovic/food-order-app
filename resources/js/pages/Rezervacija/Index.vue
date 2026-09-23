<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/components/Modal.vue'
import { promeniStatus } from '@/actions/App/Http/Controllers/RezervacijaController'

const props = defineProps({ rezervacije: Array, statusi: Array, title: String })

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

function izmeniStatus(rezervacija, statusId) {
  const action = promeniStatus(rezervacija.id)
  router.visit(action.url, {
    method: action.method,
    data: { status_id: statusId },
    preserveScroll: true,
  })
}

function danUNedelji(datum) {
  const dan= new Date(datum + 'T00:00:00').toLocaleDateString('sr-Latn-RS', {
    weekday: 'long',
  })

  return dan.charAt(0).toUpperCase() +dan.slice(1)
}
</script>

<template>
  <div class="pdf-options">
      <a href="/pdf/nalepnice" target="_blank" class="button-alt">Odštampaj nalepnice</a>
      <a href="/pdf/orders" target="_blank" class="button-alt">Radni nalog</a>
  </div>
  <table class="table">
    <thead>
      <tr>
        <th>Dan</th>
        <th>Datum</th>
        <th>Termin</th>
        <th>Korisnik</th>
        <th>Ukupno</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="r in rezervacije" :key="r.id">
        <td>{{ danUNedelji(r.datum) }}</td>
        <td>{{ r.datum }}</td>
        <td>{{ r.termin }}:00</td>
        <td>{{ r.user?.name }}</td>
        <td class="price-cell">{{ ukupno(r) }} RSD</td>
        <td>
          <select :value="r.status_id" @change="izmeniStatus(r, +$event.target.value)">
            <option v-for="s in statusi" :key="s.id" :value="s.id">{{ s.naziv_statusa }}</option>
          </select>
        </td>
        <td><button class="dugme" @click="otvoriDetalje(r)">Detalji</button></td>
      </tr>
    </tbody>
  </table>

  <Modal :show="showDetalji" @close="zatvoriDetalje">
    <h2 class="section-heading">Rezervacija — {{ viewing?.datum }}, {{ viewing?.termin }}:00</h2>

    <p><strong>Korisnik:</strong> {{ viewing?.user?.name }}</p>
    <p><strong>Adresa:</strong> {{ viewing?.adresa }}</p>
    <p v-if="viewing?.napomena"><strong>Napomena:</strong> {{ viewing.napomena }}</p>

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