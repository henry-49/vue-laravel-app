<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({ idea: Object })

const form = useForm({
  title: props.idea.title,
  description: props.idea.description,
  category: props.idea.category,
})

function submit() {
  form.put(route('ideen.update', props.idea.id))
}
</script>

<template>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">✏️ Idee bearbeiten</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <input v-model="form.title" type="text" placeholder="Titel" class="w-full p-2 border rounded" />
      <textarea v-model="form.description" placeholder="Beschreibung" class="w-full p-2 border rounded"></textarea>
      <input v-model="form.category" type="text" placeholder="Kategorie (optional)" class="w-full p-2 border rounded" />

      <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Speichern</button>
    </form>
  </div>
</template>
