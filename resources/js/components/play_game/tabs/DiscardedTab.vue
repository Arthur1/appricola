<template>
    <b-tab title="捨札">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation">職業</h3>
                <ul class="list-group">
                    <occupation-row v-for="occupation in player.discarded_occupations" :key="occupation.card_id" :occupation="occupation" @rightClick="openMenu" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement">小さい進歩</h3>
                <ul class="list-group">
                    <improvement-row v-for="improvement in player.discarded_improvements" :key="improvement.card_id" :improvement="improvement" @rightClick="openMenu" />
                </ul>
            </div>
        </div>
        <vue-simple-context-menu
            :elementId="`discardedTabMenu`"
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
    props: ['player'],
    data() {
        return {
            options: [
                {
                    name: '手札に戻す',
                    slug: 'unplay',
                    class: 'text-danger',
                },
            ],
        }
    },
    methods: {
        openMenu(event, card) {
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
    }
}
</script>
