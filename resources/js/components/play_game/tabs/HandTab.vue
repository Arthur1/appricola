<template>
    <b-tab title="自分の手札">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation">職業</h3>
                <ul class="list-group">
                    <occupation-row v-for="occupation in player.hand_occupations" :key="occupation.card_id" :occupation="occupation" @rightClick="openMenu" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement">小さい進歩</h3>
                <ul class="list-group">
                    <improvement-row v-for="improvement in player.hand_improvements" :key="improvement.card_id" :improvement="improvement" @rightClick="openMenu" />
                </ul>
            </div>
        </div>
        <vue-simple-context-menu
            :elementId="`cardMenu`"
            :options="options"
            :ref="'cardMenu'"
            @option-clicked="optionClicked"
        />
    </b-tab>
</template>
<script>
import PlayGameMixin from '../../../mixins/play_game.js'
export default {
    mixins: [PlayGameMixin],
    components: {
    },
    props: ['player'],
    data() {
        return {
            options: [
                {
                    name: 'プレイする',
                    slug: 'play',
                },
                {
                    name: '捨て札にする',
                    slug: 'discard',
                }
            ]
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
