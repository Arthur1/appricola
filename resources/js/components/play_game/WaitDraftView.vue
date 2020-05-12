<template>
    <div class="container">
        <game-info :game="game" />
        <h1 class="text-primary mt-4">ドラフト待機中 [{{ game.my_player.set_id }}]</h1>
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
}
</script>
