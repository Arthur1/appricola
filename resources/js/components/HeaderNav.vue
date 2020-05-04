<template>
    <div>
        <nav class="navbar navbar-dark navbar-expand-md navbar-light bg-primary shadow-sm">
            <router-link class="navbar-brand" to="/">AgriCompanion</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item" v-if="$store.getters.isLoggedIn">
                        <router-link to="/home" class="nav-link">ゲーム一覧</router-link>
                    </li>
                    <li class="nav-item" v-if="$store.getters.isLoggedIn">
                        <router-link to="/create_game" class="nav-link">ゲーム作成</router-link>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown" v-if="$store.getters.isLoggedIn">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                            {{ $store.state.name  }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/logout" @click.stop.prevent="logout" class="dropdown-item">ログアウト</a>
                        </div>
                    </li>
                    <li class="nav-item" v-else>
                        <router-link to="/login" class="nav-link">ログイン</router-link>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>
<script>
export default {
    methods: {
        logout: function() {
            axios.get('/sanctum/csrf-cookie').then(res=> {
                axios.post('/api/logout').then(res => {
                    this.$store.dispatch('setUser').then(() => {
                        this.$toast.warning('ログアウトしました')
                        this.$router.push('/login')
                    })
                }).catch(this.formErrorsToast)
            }).catch(this.csrfErrorToast)
        }
    }
}
</script>

