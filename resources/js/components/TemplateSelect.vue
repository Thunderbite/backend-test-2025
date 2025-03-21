<template>
    <input type="hidden" :name="props.name" :value="value" />
    <div>{{ name }}</div>
    <img :src="image" />
    <a @click="test">Load single</a>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps([
    "url",
    "name",
    "old"
])

const data = ref({...props.old})

const value = computed(() => JSON.stringify(data.value) )
const image = computed(() => props.url + data.value?.name + "/" + data.value.files?.thumbnail )
const name = computed(() => data.value.metadata?.name || "none" )

function test() {
    // load static data
    data.value = {
        name: "classic-single-wheel",
        version: "1.0.0",
        metadata: {
            name: "Thunderbite Classic Single Wheel",
            description: "Our classic single wheel. Good choice to base your own unique designs on."
        },
        files: {
            thumbnail: "7a7c7db2af1d488d2f34dc960613d14b8308330d.webp",
            video: "cf50a021cb836dfa6a57f234759da2bab2f788a6.mp4"
        }
    }
}
</script>

<style module>
</style>