<template>
    <div class="container">
        <game-info :game="game" />
        <h1 class="text-primary mt-4">カード一覧</h1>
        <p>カードの各種操作は右クリックのメニューから行えます。</p>
        <b-tabs content-class="mt-3">
            <hand-tab v-if="game.my_player" :player="game.my_player" @setIsLoading="setIsLoading" />
            <discarded-tab v-if="game.my_player" :player="game.my_player" @setIsLoading="setIsLoading" />
            <played-tab v-for="player in game.players" :key="player.id" :player="player" :my_player="game.my_player" @setIsLoading="setIsLoading" />
        </b-tabs>
    </div>
</template>
<script>
import PlayedTab from './tabs/PlayedTab.vue'
import HandTab from './tabs/HandTab.vue'
import DiscardedTab from './tabs/DiscardedTab.vue'
import PlayGameMixin from '../../mixins/play_game'
export default {
    mixins: [PlayGameMixin],
    components: {
        PlayedTab,
        HandTab,
        DiscardedTab,
    },
    props: ['game'],
    methods: {
        setIsLoading(value) {
            this.$emit('setIsLoading', value)
        }
    }
}
</script>
