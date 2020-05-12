<template>
    <div>
        <b-overlay :show="isLoading" rounded="sm">
            <div class="container" style="height: 100vh;" v-if="! game.id"></div>
            <play-view v-else-if="isPlayView" :game="game" @setIsLoading="setIsLoading" />
            <guest-view v-else-if="isGuestView" />
            <draft-view v-else-if="isDraftView" :game="game" @updateGame="updateGame" @setIsLoading="setIsLoading" />
            <wait-draft-view v-else-if="isWaitDraftView" :game="game" />
        </b-overlay>
    </div>
</template>
<script>
import PlayView from '../components/play_game/PlayView.vue'
import GuestView from '../components/play_game/GuestView.vue'
import DraftView from '../components/play_game/DraftView.vue'
import WaitDraftView from '../components/play_game/WaitDraftView.vue'
import UtilsMixin from '../mixins/utils.js'
export default {
    mixins: [UtilsMixin],
    components: {
        DraftView,
        WaitDraftView,
        GuestView,
        PlayView,
    },
    data() {
        return {
            game: {},
            isLoading: true,
        }
    },
    created() {
        this.fetchData().then(res => {
            if (this.isDraftView) {
                this.$toast.info('ピックするカードを選択してください')
            }
            Echo.private(`game.${this.game.id}`).listen('GamePickedEvent', e => {
                if (! this.game.my_player) return
                if (this.isWaitDraftView && e.next_player_order == this.game.my_player.player_order) {
                    this.fetchData().then(() => {
                        this.$toast.info('ピックするカードを選択してください')
                    }).catch()
                }
            }).listen('GameUpdateEvent', e => {
                this.fetchData().then(() => {
                    this.$toast.info(e.message)
                }).catch()
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
            this.isLoading = true
            return new Promise((resolve, reject) => {
                axios.get(`/api/games/${this.$route.params.id}/states`).then(res => {
                    this.game = res.data
                    this.isLoading = false
                    resolve(res)
                }).catch(err => {
                    this.isLoading = false
                    this.errorsToast(err)
                    reject(err)
                })
            })
        },
        updateGame(game) {
            this.isLoading = false
            this.game = game
        },
        setIsLoading(value) {
            this.isLoading = value
        }
    }
}
</script>
