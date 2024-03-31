<script setup>
import { RouterView } from "vue-router";
import Toast from 'primevue/toast';

</script>

<template>
    <component>
        <Toast />
        <!-- //when router changes here the changed content will arive -->
        <RouterView />
    </component>
</template>
