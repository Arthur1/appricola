<template>
    <div id="app">
        <header v-if="showHeader"><header-nav /></header>
        <main><router-view /></main>
        <div v-if="isLoading" class="spinnerBox d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>
<script>
import HeaderNav from './components/HeaderNav'

export default {
    components: {
        HeaderNav
    },
    computed: {
        showHeader() {
            return this.$route.name !== 'login' && this.$route.name !== 'register'
        },
        isLoading() {
            let requiresAuth = typeof this.$route.meta.requiresAuth === 'undefined' || this.$route.meta.requiresAuth
            return requiresAuth && ! this.$store.state.isInitialized
        }
    }
}
</script>
<style scoped>
.spinnerBox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.1);
}
.spinnerBox .spinner-border {
    width: 4rem;
    height: 4rem;
    margin: auto;
}
</style>
