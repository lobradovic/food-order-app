<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Modal from '@/components/Modal.vue'
import { useDnevnaPonudaForm } from '@/composables/useDnevnaPonudaForm'
import { store, update, destroy } from '@/actions/App/Http/Controllers/DnevnaPonudaController'

defineOptions({ layout: false })

const pretraga = ref('')

const filtriranaJela = computed(() => {
  const upit = pretraga.value.trim().toLowerCase()
  if (!upit) return props.jelos
  return props.jelos.filter((j) => j.naziv_jela.toLowerCase().includes(upit))
})

const props = defineProps({ ponude: Array, jelos: Array })

const showModal = ref(false)
const editing = ref(null)

const showView = ref(false)
const viewingPonuda = ref(null)

const { form, toggleJelo, isSelected, selected, reset } = useDnevnaPonudaForm()

function openCreate() {
  editing.value = null
  reset({ datum: '', aktivna: true, jela: [] })
  showModal.value = true
}

function openEdit(ponuda) {
  editing.value = ponuda
  reset({
    datum: ponuda.datum,
    aktivna: ponuda.aktivna,
    jela: ponuda.jelos.map((j) => ({
      id: j.id,
      cena: j.pivot.cena,
      kolicina_dostupna: j.pivot.kolicina_dostupna,
    })),
  })
  showModal.value = true
}

function openView(ponuda) {
  viewingPonuda.value = ponuda
  showView.value = true
}

function close() {
  showModal.value = false
}

function closeView() {
  showView.value = false
}

function submit() {
  const action = editing.value ? update(editing.value.id) : store()
  form.submit(action, { onSuccess: close })
}

function obrisi(ponuda) {
  if (confirm(`Obrisati ponudu za ${ponuda.datum}?`)) {
    router.delete(destroy(ponuda.id).url)
  }
}

function danUNedelji(datum) {
  const dan = new Date(datum + 'T00:00:00').toLocaleDateString('sr-Latn-RS', {
    weekday: 'long',
  })

  return dan.charAt(0).toUpperCase() + dan.slice(1)
}
</script>

<template>
  <AppLayout title="Dnevne ponude">
    <button class="dugme razdvoj" @click="openCreate">Nova ponuda</button>

    <table class="table">
      <thead>
        <tr>
          <th>Dan</th>
          <th>Datum</th>
          <th>Status</th>
          <th colspan="3">Opcije</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="ponuda in ponude" :key="ponuda.id">
          <td>{{ danUNedelji(ponuda.datum) }}</td>
          <td>{{ ponuda.datum }}</td>
          <td>{{ ponuda.aktivna ? 'Aktivna' : 'Neaktivna' }}</td>
          <td><button class="dugme" @click="openView(ponuda)">Pregled</button></td>
          <td><button class="dugme" @click="openEdit(ponuda)">Izmeni</button></td>
          <td><button class="dugme" @click="obrisi(ponuda)">Obriši</button></td>
        </tr>
      </tbody>
    </table>

    <Modal :show="showView" @close="closeView">
      <h2 class="section-heading">Jela za {{ viewingPonuda?.datum }}</h2>
      <ul style="list-style: none; padding: 0;">
        <li v-for="jelo in viewingPonuda?.jelos ?? []" :key="jelo.id" class="razdvoj" style="margin-bottom: .8em;">
          {{ jelo.naziv_jela }} — <span class="price-cell">{{ jelo.pivot.cena }} RSD</span>
          <span v-if="jelo.pivot.kolicina_dostupna !== null">
            (dostupno: {{ jelo.pivot.kolicina_dostupna }})
          </span>
        </li>
      </ul>
      <button type="button" class="dugme" @click="closeView">Zatvori</button>
    </Modal>

    <!-- Modal za kreiranje/izmenu -->
    <Modal :show="showModal" @close="close">
      <h2 class="section-heading">{{ editing ? 'Izmena dnevne ponude' : 'Nova dnevna ponuda' }}</h2>

      <div class="razdvoj">
        <label>Pretraga jela</label><br>
        <input type="text" v-model="pretraga" placeholder="Naziv jela..." style="width: 100%;" />
      </div>
      <form @submit.prevent="submit">
        <div class="razdvoj">
          <label>Datum</label><br>
          <input type="date" v-model="form.datum" />
          <div v-if="form.errors.datum" style="color:var(--maroon)">{{ form.errors.datum }}</div>
        </div>

        <div class="razdvoj">
          <label><input type="checkbox" v-model="form.aktivna" /> Aktivna</label>
        </div>

        <div class="menu razdvoj">
          <div class="menuItem" v-for="jelo in filtriranaJela" :key="jelo.id">
            <label>
              <input type="checkbox" :checked="isSelected(jelo.id)" @change="toggleJelo(jelo, $event.target.checked)" />
              {{ jelo.naziv_jela }} ({{ jelo.cena }} RSD)
            </label>
          </div>
          <p v-if="filtriranaJela.length === 0" style="color:var(--item-text);">Nema jela koja odgovaraju pretrazi.</p>
        </div>
        <div v-if="form.errors.jela" style="color:var(--maroon)">{{ form.errors.jela }}</div>
        <div v-if="form.errors.jela" style="color:var(--maroon)">{{ form.errors.jela }}</div>

        <div style="display:flex; gap:1em; margin-top:1em;">
          <button type="submit" class="dugme" :disabled="form.processing">Sačuvaj</button>
          <button type="button" class="dugme" @click="close">Otkaži</button>
        </div>
      </form>
    </Modal>
  </AppLayout>
</template>