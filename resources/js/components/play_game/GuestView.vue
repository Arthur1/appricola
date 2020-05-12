<template>
    <div class="container">
        <game-info :game="game" />
        <h1 class="text-primary mt-4">ドラフト中</h1>
        <p>ドラフトが終わるまでしばらくお待ちください。</p>
    </div>
</template>
<script>
import PlayGameMixin from '../../mixins/play_game'
export default {
    mixins: [PlayGameMixin],
}
</script>
