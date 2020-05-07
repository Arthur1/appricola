<template>
    <div class="container">
        <h1 class="text-primary mt-4">ゲーム一覧</h1>
        <div class="list-group">
            <router-link v-for="game in sortedGames" :key="game.game.id" :to="{ name: 'play_game', params: { id: game.game.id } }" class="list-group-item list-group-item-action">
                <span class="mr-1rem">{{ game.game.players_number }}人ゲーム(game.game.pool.name)</span><br>
                <span class="mr-1rem">参加者:</span><span class="mr-1rem" v-for="player in game.game.players" :key="player.player_order">{{ player.user.name }}</span><br>
                <span class="mr-1rem">作成日時：{{ game.game.created_at }}</span>
            </router-link>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            games: [],
        }
    },
    created() {
        axios.get('/api/games/list').then(res => {
            this.games = res.data
        })
    },
    computed: {
        sortedGames() {
            return this.games.reverse()
        }
    }
}
</script>
