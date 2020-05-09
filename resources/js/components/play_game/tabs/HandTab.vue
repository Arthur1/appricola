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
        <div class="row mt-4">
            <div class="col-sm-6">
                <button type="button" class="btn btn-occupation text-white btn-block" @click="$bvModal.show('drawOccupationsModal')">職業を引く</button>
            </div>
            <div class="col-sm-6">
                <button type="button" class="btn btn-minor_improvement btn-block" @click="$bvModal.show('drawImprovementsModal')">小さい進歩を引く</button>
            </div>
        </div>
        <b-modal
            id="drawOccupationsModal"
            title="職業を引く"
            @show="resetDrawOccupationsModal"
            @hidden="resetDrawOccupationsModal"
            @ok="okDrawOccupationsModal"
        >
            <form @submit.stop.prevent="drawOccupations">
                <div class="form-group">
                    <label for="form_draw_occupations_number">ドロー枚数</label>
                    <input type="number" class="form-control" id="form_draw_occupations_number" v-model="draw_occupations_number" required min="1" max="7">
                </div>
            </form>
        </b-modal>
        <b-modal
            id="drawImprovementsModal"
            title="小さい進歩を引く"
            @show="resetDrawImprovementsModal"
            @hidden="resetDrawImprovementsModal"
            @ok="okDrawImprovementsModal"
        >
            <form @submit.stop.prevent="drawImprovements">
                <div class="form-group">
                    <label for="form_draw_improvements_number">ドロー枚数</label>
                    <input type="number" class="form-control" id="form_draw_improvements_number" v-model="draw_improvements_number" required min="1" max="7">
                </div>
            </form>
        </b-modal>
        <vue-simple-context-menu
            :elementId="`handTabMenu`"
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
            draw_occupations_number: 1,
            draw_improvements_number: 1,
            options: [
                {
                    name: 'プレイする',
                    slug: 'play',
                },
                {
                    name: '捨て札にする',
                    slug: 'discard',
                    class: 'text-danger',
                },
            ],
        }
    },
    methods: {
        openMenu(event, card) {
            this.$refs.cardMenu.showMenu(event, card)
        },
        optionClicked(event) {
            switch (event.option.slug) {
                case 'play':
                    this.playCard(event)
                    break
                case 'discard':
                    console.log('discard')
                    break
            }
        },
        playCard(event) {
            let type = event.item.card.type === 'occupation' ? 'occupations' : 'improvements'
            axios.put(`/api/games/${this.player.game_id}/${type}/${event.item.id}/play`).then().catch(err => {
                this.errorsToast(err)
            })
        },
        resetDrawOccupationsModal() {
            this.draw_occupations_number = 1
        },
        resetDrawImprovementsModal() {
            this.draw_improvements_number = 1
        },
        okDrawOccupationsModal(e) {
            e.preventDefault()
            this.drawOccupations()
        },
        okDrawImprovementsModal(e) {
            e.preventDefault()
            this.drawImprovements()
        },
        drawOccupations() {
            const payload = {
                draw_number: this.draw_occupations_number,
            }
            axios.post(`/api/games/${this.player.game_id}/draw_occupations`, payload).then().catch(err => {
                this.errorsToast(err)
            })
            this.$nextTick(() => {
                this.$bvModal.hide('drawOccupationsModal')
            })
        },
        drawImprovements() {
            const payload = {
                draw_number: this.draw_improvements_number,
            }
            axios.post(`/api/games/${this.player.game_id}/draw_improvements`, payload).then().catch(err => {
                this.errorsToast(err)
            })
            this.$nextTick(() => {
                this.$bvModal.hide('drawImprovementsModal')
            })
        },
    }
}
</script>
