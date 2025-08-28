<script setup>
    import { useForm } from '@inertiajs/vue3'
    import { onMounted } from 'vue'

    const props = defineProps({
    ideas: Array,
    })

    const form = useForm({
    title: '',
    description: '',
    category: '',
    })

    function destroy(id) {
    if (confirm('Sicher löschen?')) {
        router.delete(route('ideen.destroy', id), {
        preserveScroll: true,
        preserveState: true,
        only: ['ideas'],
        })
        }
    }

    function submit() {
    form.post(route('ideen.store'), {
        onSuccess: () => form.reset(),
    })

    function vote(ideaId, direction) {
    router.post(route('ideen.vote', ideaId), { direction }, {
        preserveScroll: true,
        preserveState: true,
        only: ['ideas'],
        })
    }

    // Polling: alle 5 Sekunden neu laden
    onMounted(() => {
    setInterval(() => {
        router.reload({ only: ['ideas'] })
    }, 5000)
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
            <!-- Edit / Delete -->
            <a :href="route('ideen.edit', idea.id)" class="ml-4 text-blue-500">Bearbeiten</a>
            <button @click="destroy(idea.id)" class="text-red-500">Löschen</button>
        </div>

           <!-- Feedback -->
        <div class="mt-2 text-sm">
            <span v-if="idea.feedback">
            💬 Feedback: {{ idea.feedback }}
            </span>
            <span v-else class="text-gray-400">
            ⏳ Feedback wird generiert…
            </span>
        </div>

        <!-- Voting -->
        <div class="mt-3 flex items-center gap-2">
          <button @click="vote(idea.id, 'up')" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">👍</button>
          <button @click="vote(idea.id, 'down')" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">👎</button>
          <span class="ml-2 font-medium">Votes: {{ idea.votes }}</span>
        </div>

      </div>
    </div>
  </div>


</template>

