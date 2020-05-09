<template>
    <b-tab :title="title">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation">職業</h3>
                <ul class="list-group" v-if="isMe">
                    <occupation-row v-for="occupation in player.played_occupations" :key="occupation.card_id" :occupation="occupation" />
                </ul>
                <ul class="list-group" v-else>
                    <occupation-row v-for="occupation in player.played_occupations" :key="occupation.card_id" :occupation="occupation" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement">小さい進歩</h3>
                <ul class="list-group" v-if="isMe">
                    <improvement-row v-for="improvement in player.played_improvements" :key="improvement.card_id" :improvement="improvement" />
                </ul>
                <ul class="list-group" v-else>
                    <improvement-row v-for="improvement in player.played_improvements" :key="improvement.card_id" :improvement="improvement" />
                </ul>
            </div>
        </div>
    </b-tab>
</template>
<script>
import PlayGameMixin from '../../../mixins/play_game.js'
// import PlayedOccupationRow from '../card_row/PlayedOccupationRow.vue'
// import PlayedImprovementRow from '../card_row/PlayedImprovementRow.vue'
export default {
    mixins: [ PlayGameMixin ],
    props: ['player', 'my_player'],
    computed: {
        isMe() {
            if (! this.my_player) return false
            return this.player.player_order === this.my_player.player_order
        },
        title() {
            let title = `${this.player.player_order}番手`
            if (this.isMe) title += '(自分)'
            return title
        }
    },
    methods: {
        openMenu(event, card) {
            console.log(card)
            this.$refs.cardMenu.showMenu(event, card)
        },
        optionClicked(event) {
            console.log(event)
        }
    }
}
</script>
