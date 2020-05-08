<template>
    <div>
        <div v-if="! this.game.id">
            <div class="d-flex justify-content-center mt-4">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <draft-view v-else-if="isDraftView" :game="game" @updateGame="updateGame" />
        <wait-draft-view v-else-if="isWaitDraftView" :game="game" />
    </div>
</template>
<script>
import DraftView from '../components/play_game/DraftView.vue'
import WaitDraftView from '../components/play_game/WaitDraftView.vue'
export default {
    components: {
        DraftView,
        WaitDraftView,
    },
    data() {
        return {
            game: {},
        }
    },
    created() {
        axios.get(`/api/games/${this.$route.params.id}/states`).then(res => {
            this.game = res.data
        }).catch(err => {})
    },
    computed: {
        isDraftView() {
            if (! this.game.my_player) return false;
            return Boolean(this.game.my_player.pick_occupations.length && this.game.my_player.pick_improvements.length)
        },
        isWaitDraftView() {
            return true;
        }
    },
    methods: {
        updateGame(game) {
            this.game = game
        }
    }
}
</script>
