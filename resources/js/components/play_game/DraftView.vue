<template>
    <div class="container">
        <h1 class="text-primary mt-4">ドラフト{{ pickOrder }}手目</h1>
        <div class="row">
            <div class="col-sm-6">
                <h2 class="text-occupation mt-3">職業</h2>
                <ul class="list-group">
                    <occupation-row v-for="occupation in game.my_user.pick_occupations" :key="occupation.card_id" :occupation="occupation" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h2 class="text-minor_improvement mt-3">小さい進歩</h2>
                <ul class="list-group">
                    <improvement-row v-for="improvement in game.my_user.pick_improvements" :key="improvement.card_id" :improvement="improvement" />
                </ul>
            </div>
        </div>
        <form @submit.prevent="select">
            <div class="row mt-4">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_pick_occupation" class="text-occupation">ピックする職業</label>
                        <select class="form-control" id="form_pick_occupation" v-model="pick_occupation" required>
                            <option v-for="occupation in game.my_user.pick_occupations" :key="occupation.id" :value="occupation.id">{{ occupation.card.japanese_name }} [{{ occupation.card.id_display }}]</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_pick_improvement" class="text-minor_improvement">ピックする小進歩</label>
                        <select class="form-control" id="form_pick_improvement" v-model="pick_improvement" required>
                            <option v-for="improvement in game.my_user.pick_improvements" :key="improvement.id" :value="improvement.id">{{ improvement.card.japanese_name }} [{{ improvement.card.id_display }}]</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">選択する</button>
                    <button type="button" class="btn btn-secondary" @click="$bvModal.show('modal-hand_cards')">現在の手札</button>
                    <b-modal id="modal-hand_cards" size="xl" title="現在の手札" hide-footer>
                        <div class="row">
                            <div class="col-sm-6">
                                <h2 class="text-occupation">職業</h2>
                                <ul class="list-group">
                                    <occupation-row v-for="occupation in game.my_user.hand_occupations" :key="occupation.card_id" :occupation="occupation" />
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <h2 class="text-minor_improvement">小さい進歩</h2>
                                <ul class="list-group">
                                    <improvement-row v-for="improvement in game.my_user.hand_improvements" :key="improvement.card_id" :improvement="improvement" />
                                </ul>
                            </div>
                        </div>
                    </b-modal>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import OccupationRow from '../card_row/OccupationRow.vue'
import ImprovementRow from '../card_row/ImprovementRow.vue'
export default {
    props: ['game'],
    components: {
        OccupationRow,
        ImprovementRow
    },
    data() {
        return {
            pick_occupation: null,
            pick_improvement: null,
        }
    },
    computed: {
        pickOrder() {
            return this.game.my_user.hand_occupations.length + 1
        }
    },
    methods: {
        select() {}
    }
}
</script>
