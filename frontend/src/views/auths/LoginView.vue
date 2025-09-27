<script setup>
import { useTitle } from '@vueuse/core';
import { useI18n } from 'vue-i18n';

const { t } = useI18n()

useTitle(t("login"));



</script>

<template>
    <div>

    </div>
</template>