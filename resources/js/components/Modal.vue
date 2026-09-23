<!-- resources/js/Components/Modal.vue -->
<script setup>
defineProps({ show: Boolean })
const emit = defineEmits(['close'])
</script>

<template>
  <div v-if="show" class="modal-overlay" @click.self="emit('close')">
    <div class="modal-box">
      <slot />
    </div>
  </div>
</template>