<template>
    <div>
        <div v-if="! this.game.id">
            <div class="d-flex justify-content-center mt-4">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <play-view v-else-if="isPlayView" :game="game" />
        <guest-view v-else-if="isGuestView" />
        <draft-view v-else-if="isDraftView" :game="game" @updateGame="updateGame" />
        <wait-draft-view v-else-if="isWaitDraftView" :game="game" />
    </div>
</template>
<script>
import PlayView from '../components/play_game/PlayView.vue'
import GuestView from '../components/play_game/GuestView.vue'
import DraftView from '../components/play_game/DraftView.vue'
import WaitDraftView from '../components/play_game/WaitDraftView.vue'
export default {
    components: {
        DraftView,
        WaitDraftView,
        GuestView,
        PlayView,
    },
    data() {
        return {
            game: {},
        }
    },
    created() {
        axios.get(`/api/games/${this.$route.params.id}/states`).then(res => {
            this.game = res.data
            if (this.isDraftView) {
                this.$toast.info('ピックするカードを選択してください')
            }
            Echo.private(`game.${this.game.id}`).listen('GamePickedEvent', (e) => {
                if (! this.game.my_player) return
                if (this.isWaitDraftView && e.next_player_order == this.game.my_player.player_order) this.fetchData()
            })
        }).catch(err => {})
    },
    beforeDestroy() {
        Echo.leave(`game.${this.game.id}`)
    },
    computed: {
        isPlayView() {
            return ! this.game.is_picking
        },
        isGuestView() {
            if (this.gameView) return false
            return ! this.game.my_player
        },
        isDraftView() {
            if (this.isGuestView) return false
            return Boolean(this.game.my_player.pick_occupations.length && this.game.my_player.pick_improvements.length)
        },
        isWaitDraftView() {
            if (this.isDraftView) return false
            return true
        }
    },
    methods: {
        fetchData() {
            axios.get(`/api/games/${this.$route.params.id}/states`).then(res => {
                this.game = res.data
                if (this.isDraftView) {
                    this.$toast.info('ピックするカードを選択してください')
                }
            }).catch(err => {})
        },
        updateGame(game) {
            this.game = game
        }
    }
}
</script>
