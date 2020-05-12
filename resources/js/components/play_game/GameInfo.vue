<template>
    <div class="list-group mt-4">
        <div class="list-group-item list-group-item-info">
            <span>{{ game.players_number }}人ゲーム【{{ game.pool.name }}】<span v-if="game.my_player">{{ game.my_player.player_order }}番手</span></span><br>
            <span>参加者：{{ game.players.map(player => player.user.name) | implode }}</span><br>
            <span>作成日時：{{ game.created_at }}</span>
        </div>
    </div>
</template>
<script>
export default {
    props: ['game'],
    filters: {
        implode(array) {
            return array.join('・')
        }
    }
}
</script>>
