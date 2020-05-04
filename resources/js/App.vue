<template>
    <div id="app">
        <header v-if="showHeader"><header-nav /></header>
        <main><router-view /></main>
    </div>
</template>
<script>
import HeaderNav from './components/HeaderNav'

export default {
    components: {
        HeaderNav
    },
    computed: {
        showHeader: function() {
            return this.$route.name !== 'login' && this.$route.name !== 'register'
        }
    }
}
</script>
