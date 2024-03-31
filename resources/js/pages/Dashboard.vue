<script setup>
import Navbar from "../components/Navbar.vue";
import DashboardBody from "../components/DashboardBody.vue";
</script>
<template>
    <div class="container">
        <Navbar />
        <DashboardBody />
    </div>
</template>
<style>
.p-button {
    background-color: rgb(65, 235, 178);
    padding: 7px 12px;
    border: 1px solid rgb(147, 147, 143);
}
.p-button > span {
}
</style>
