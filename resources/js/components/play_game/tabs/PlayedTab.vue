<template>
    <b-tab :title="title">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation">職業</h3>
                <ul class="list-group">
                    <occupation-row v-for="occupation in player.played_occupations" :key="occupation.card_id" :occupation="occupation" @rightClick="openMenu" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement">小さい進歩</h3>
                <ul class="list-group">
                    <improvement-row v-for="improvement in player.played_improvements" :key="improvement.card_id" :improvement="improvement" @rightClick="openMenu" />
                </ul>
            </div>
        </div>
        <vue-simple-context-menu
            :elementId="`playedTabMenu-${this.player.id}`"
            :options="options"
            :ref="'cardMenu'"
            @option-clicked="optionClicked"
        />
    </b-tab>
</template>
<script>
import PlayGameMixin from '../../../mixins/play_game.js'
import UtilsMixin from '../../../mixins/utils.js'
export default {
    mixins: [PlayGameMixin, UtilsMixin],
    props: ['player', 'my_player'],
    data() {
        return {
            options: []
        }
    },
    computed: {
        isMe() {
            if (! this.my_player) return false
            return this.player.player_order === this.my_player.player_order
        },
        title() {
            let title = this.isMe ? '★' : ''
            title += `[${this.player.player_order}] ${this.player.user.name}`
            return title
        }
    },
    methods: {
        openMenu(event, card) {
            if (! this.isMe) return
            let options = []
            if (card.status === 'played') {
                options.push({
                    name: '裏返す',
                    slug: 'face_down',
                })
            } else {
                options.push({
                    name: '表にする',
                    slug: 'face_up',
                })
            }
            options.push({
                name: '手札に戻す',
                slug: 'unplay',
            })
            options.push({
                name: '左隣に渡す',
                slug: 'pass_left',
            })
            options.push({
                name: '捨て札にする',
                slug: 'discard',
                class: 'text-danger',
            })
            this.options = options
            Object.defineProperty(event, 'pageY', {
                value: event.pageY - 79,
                writable: true
            })
            this.$refs.cardMenu.showMenu(event, card)
        },
        optionClicked(event) {
            switch (event.option.slug) {
                case 'unplay':
                    this.unplayCard(event)
                    break
                case 'face_up':
                    this.faceUpCard(event)
                    break
                case 'face_down':
                    this.faceDownCard(event)
                    break
                case 'pass_left':
                    this.passLeft(event)
                    break
                case 'discard':
                    this.discardCard(event)
                    break
            }
        },
        unplayCard(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            this.$emit('setIsLoading', true)
            axios.put(`/api/games/${this.player.game_id}/${type}/${event.item.id}/unplay`).then().catch(err => {
                this.$emit('setIsLoading', false)
                this.errorsToast(err)
            })
        },
        faceUpCard(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            this.$emit('setIsLoading', true)
            axios.put(`/api/games/${this.player.game_id}/${type}/${event.item.id}/face_up`).then().catch(err => {
                this.$emit('setIsLoading', false)
                this.errorsToast(err)
            })
        },
        faceDownCard(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            this.$emit('setIsLoading', true)
            axios.put(`/api/games/${this.player.game_id}/${type}/${event.item.id}/face_down`).then().catch(err => {
                this.$emit('setIsLoading', false)
                this.errorsToast(err)
            })
        },
        discardCard(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            this.$emit('setIsLoading', true)
            axios.put(`/api/games/${this.player.game_id}/${type}/${event.item.id}/discard`).then().catch(err => {
                this.$emit('setIsLoading', false)
                this.errorsToast(err)
            })
        },
        passLeft(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            this.$emit('setIsLoading', true)
            axios.post(`/api/games/${this.player.game_id}/${type}/${event.item.id}/pass_left`).then().catch(err => {
                this.$emit('setIsLoading', false)
                this.errorsToast(err)
            })
        },
    }
}
</script>
