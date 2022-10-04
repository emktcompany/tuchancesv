<template lang="html">
  <import-form :action="`/admin/programs/${$route.params.id}/import`" />
</template>

<script>
import FormPage from '@/components/admin/FormPage.vue';

export default {
  extends: FormPage,
}
</script>
