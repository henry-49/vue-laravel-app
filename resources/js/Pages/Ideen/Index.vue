<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  ideas: Array,
})

const form = useForm({
  title: '',
  description: '',
  category: '',
})

function submit() {
  form.post(route('ideen.store'), {
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">💡 Ideen-Sammlung</h1>

    <!-- Formular -->
    <form @submit.prevent="submit" class="space-y-4 mb-8">
      <input v-model="form.title" type="text" placeholder="Titel"
        class="w-full p-2 border rounded" />
      <textarea v-model="form.description" placeholder="Beschreibung"
        class="w-full p-2 border rounded"></textarea>
      <input v-model="form.category" type="text" placeholder="Kategorie (optional)"
        class="w-full p-2 border rounded" />

      <button type="submit"
        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        Hinzufügen
      </button>
    </form>

    <!-- Liste -->
    <div class="space-y-2">
      <div v-for="idea in props.ideas" :key="idea.id"
        class="p-3 border rounded bg-white shadow-sm">
        <h2 class="font-semibold">{{ idea.title }}</h2>
        <p class="text-gray-700 text-sm">{{ idea.description }}</p>
        <div class="text-xs text-gray-500 flex justify-between">
          <span v-if="idea.category">Kategorie: {{ idea.category }}</span>
          <span>{{ new Date(idea.created_at).toLocaleString() }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

