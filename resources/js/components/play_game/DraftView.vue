<template>
    <div class="container">
        <game-info :game="game" />
        <h1 class="text-primary mt-4">ドラフト{{ pickOrder }}手目 [{{ game.my_player.set_id }}]</h1>
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation mt-3">職業</h3>
                <ul class="list-group">
                    <occupation-row v-for="occupation in game.my_player.pick_occupations" :key="occupation.card_id" :occupation="occupation" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement mt-3">小さい進歩</h3>
                <ul class="list-group">
                    <improvement-row v-for="improvement in game.my_player.pick_improvements" :key="improvement.card_id" :improvement="improvement" />
                </ul>
            </div>
        </div>
        <form @submit.prevent="pick">
            <div class="row mt-4">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_picked_occupation" class="text-occupation">ピックする職業</label>
                        <select class="form-control" id="form_picked_occupation" v-model="picked_occupation" required>
                            <option v-for="occupation in game.my_player.pick_occupations" :key="occupation.id" :value="occupation.id">{{ occupation.card.japanese_name }} [{{ occupation.card.id_display }}]</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="form_picked_improvement" class="text-minor_improvement">ピックする小進歩</label>
                        <select class="form-control" id="form_picked_improvement" v-model="picked_improvement" required>
                            <option v-for="improvement in game.my_player.pick_improvements" :key="improvement.id" :value="improvement.id">{{ improvement.card.japanese_name }} [{{ improvement.card.id_display }}]</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary" :disabled="is_processing">
                        <span v-if="is_processing">
                            <span class="spinner-border spinner-border-sm"></span>
                            <span class="sr-only">Loading...</span>
                        </span>
                        選択する
                    </button>
                </div>
            </div>
        </form>
        <h2 class="text-secondary mt-3">現在の手札</h2>
        <div class="row">
            <div class="col-sm-6">
                <h3 class="text-occupation">職業</h3>
                <ul class="list-group">
                    <occupation-row v-for="occupation in game.my_player.hand_occupations" :key="occupation.card_id" :occupation="occupation" />
                </ul>
            </div>
            <div class="col-sm-6">
                <h3 class="text-minor_improvement">小さい進歩</h3>
                <ul class="list-group">
                    <improvement-row v-for="improvement in game.my_player.hand_improvements" :key="improvement.card_id" :improvement="improvement" />
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
import PlayGameMixin from '../../mixins/play_game'
export default {
    mixins: [ PlayGameMixin ],
    props: ['game'],
    data() {
        return {
            picked_occupation: null,
            picked_improvement: null,
            is_processing: false,
        }
    },
    computed: {
        pickOrder() {
            return this.game.my_player.hand_occupations.length + 1
        }
    },
    methods: {
        pick() {
            this.is_processing = true
            let payload = {
                picked_occupation: this.picked_occupation,
                picked_improvement: this.picked_improvement,
            }
            this.$emit('setIsLoading', true)
            axios.post(`/api/games/${this.game.id}/pick`, payload).then(res => {
                this.is_processing = false
                this.$emit('updateGame', res.data)
            }).catch(err => {
                this.$emit('setIsLoading', false)
                this.is_processing = false
                console.log(err)
            })
        }
    }
}
</script>
