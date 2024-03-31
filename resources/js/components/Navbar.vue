<script setup>
import { onMounted } from 'vue';
import { ref } from 'vue';
import Button from 'primevue/button';
import Axios from '../axios/Axios.js'
const token = localStorage.getItem("token");
const loadingLogout = ref(false);

//function for handling the logout api call with loading state for loading indication in logout btn
const handleLogout = () => {
    loadingLogout.value = true
    Axios.get("auth/github/logout").then((res) => {
        localStorage.removeItem("token")
        window.location.href = "/";
    }).catch((err)=>{
        loadingLogout.value = false
    });
};

//get the personal access token for the current user so that our api call is authenticated  and save the token in locastorage
onMounted(() => {
    if (!token) {
        Axios.get("getToken").then((res) => {
            console.log("Res", res)
            localStorage.setItem("token", res.data.token)
        }).catch((err) => {
            console.log("err", err)
            handleLogout();
        })
    }
})
</script>
<template>
    <nav class="navbar p-3">
            <h2>Welcome</h2>
            <Button :loading="loadingLogout" label="Logout" @click="handleLogout"/>
    </nav>
</template>
<style>
.navbar {
    background-color: antiquewhite;
}
</style>
