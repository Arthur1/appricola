<template>
    <div>
        <draft-view v-if="isDraftView" :game="game" />
    </div>
</template>
<script>
import DraftView from '../components/play_game/DraftView.vue'
export default {
    components: {
        DraftView
    },
    data() {
        return {
            game: {},
        }
    },
    created() {
        this.game.id = this.$route.params.id
        axios.get(`/api/games/${this.$route.params.id}/states`).then(res => {
            this.game = res.data
        })
    },
    computed: {
        isDraftView() {
            if (! this.game.my_user) return false;
            return Boolean(this.game.my_user.pick_occupations.length && this.game.my_user.pick_improvements.length)
        }
    }
}
</script>
