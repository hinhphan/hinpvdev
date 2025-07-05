<script setup>
import { defineAsyncComponent } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const Layout = defineAsyncComponent(() => import(`@/layouts/${route.meta.layout || 'EmptyLayout'}.vue`))

</script>

<template>
  <Layout>
    <RouterView />
  </Layout>
</template>
