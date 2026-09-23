<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/components/Modal.vue'
import { store, update, destroy } from '@/actions/App/Http/Controllers/JeloController'

const props = defineProps({ jela: Array, title: String })

const showModal = ref(false)
const editing = ref(null)

const form = ref({
  naziv_jela: '',
  cena: null,
  opis: '',
  stalna_ponuda: false,
})
const errors = ref({})
const processing = ref(false)

function openCreate() {
  editing.value = null
  form.value = { naziv_jela: '', cena: null, opis: '', stalna_ponuda: false }
  errors.value = {}
  showModal.value = true
}

function openEdit(jelo) {
  editing.value = jelo
  form.value = {
    naziv_jela: jelo.naziv_jela,
    cena: jelo.cena,
    opis: jelo.opis,
    stalna_ponuda: jelo.stalna_ponuda,
  }
  errors.value = {}
  showModal.value = true
}

function close() {
  showModal.value = false
}

function submit() {
  processing.value = true
  const action = editing.value ? update(editing.value.id) : store()

  router.visit(action.url, {
    method: action.method,
    data: form.value,
    onSuccess: () => {
      processing.value = false
      close()
    },
    onError: (e) => {
      processing.value = false
      errors.value = e
    },
  })
}

function obrisi(jelo) {
  if (confirm(`Obrisati jelo "${jelo.naziv_jela}"?`)) {
    router.delete(destroy(jelo.id).url)
  }
}
</script>

<template>
  <button class="dugme razdvoj" @click="openCreate">Novo jelo</button>

  <table class="table">
    <thead>
      <tr>
        <th>Naziv</th>
        <th>Cena</th>
        <th>Stalna ponuda</th>
        <th colspan="2">Opcije</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="jelo in jela" :key="jelo.id">
        <td>{{ jelo.naziv_jela }}</td>
        <td class="price-cell">{{ jelo.cena }} RSD</td>
        <td>{{ jelo.stalna_ponuda ? 'Da' : 'Ne' }}</td>
        <td><button class="dugme" @click="openEdit(jelo)">Izmeni</button></td>
        <td><button class="dugme" @click="obrisi(jelo)">Obriši</button></td>
      </tr>
    </tbody>
  </table>

  <Modal :show="showModal" @close="close">
    <h2 class="section-heading">{{ editing ? 'Izmena jela' : 'Novo jelo' }}</h2>

    <form @submit.prevent="submit">
      <div class="razdvoj">
        <label>Naziv</label><br>
        <input type="text" v-model="form.naziv_jela" />
        <div v-if="errors.naziv_jela" style="color:var(--maroon)">{{ errors.naziv_jela }}</div>
      </div>

      <div class="razdvoj">
        <label>Cena</label><br>
        <input type="number" v-model.number="form.cena" />
        <div v-if="errors.cena" style="color:var(--maroon)">{{ errors.cena }}</div>
      </div>

      <div class="razdvoj">
        <label>Opis</label><br>
        <textarea v-model="form.opis" rows="3" style="width: 100%;"></textarea>
      </div>

      <div class="razdvoj">
        <label><input type="checkbox" v-model="form.stalna_ponuda" /> Stalna ponuda</label>
      </div>

      <div style="display:flex; gap:1em;">
        <button type="submit" class="dugme" :disabled="processing">Sačuvaj</button>
        <button type="button" class="dugme" @click="close">Otkaži</button>
      </div>
    </form>
  </Modal>
</template>