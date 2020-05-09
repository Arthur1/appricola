<template>
    <div class="container">
        <h1 class="text-primary mt-4">カード一覧</h1>
        <b-tabs content-class="mt-3">
            <hand-tab v-if="game.my_player" :player="game.my_player" />
            <played-tab v-for="player in game.players" :key="player.id" :player="player" :my_player="game.my_player" />
        </b-tabs>
    </div>
</template>
<script>
import PlayedTab from './tabs/PlayedTab.vue'
import HandTab from './tabs/HandTab.vue'
export default {
    components: {
        PlayedTab,
        HandTab,
    },
    props: ['game'],
}
</script>
