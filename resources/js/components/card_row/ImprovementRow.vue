<template>
    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" @click="$bvModal.show(`improvementRowModal-${improvement.card.id}`)">
        <span>{{ improvement.card.japanese_name }}</span>
        <span class="badge badge-minor_improvement text-white">{{ improvement.card.id_display }}</span>
        <b-modal :id="`improvementRowModal-${improvement.card.id}`" hide-footer>
            <template v-slot:modal-title>
                {{ improvement.card.japanese_name }} <span class="badge badge-modal badge-minor_improvement text-white">{{ improvement.card.id_display }}</span>
            </template>
            <dl>
                <dt>デッキ</dt>
                <dd>{{ improvement.card.deck.name }}</dd>
                <dt v-if="improvement.card.prerequisite">前提</dt>
                <dd v-if="improvement.card.prerequisite">{{ improvement.card.prerequisite }}</dd>
                <dt v-if="improvement.card.costs">コスト</dt>
                <dd v-if="improvement.card.costs">{{ improvement.card.costs }}</dd>
                <dt v-if="improvement.card.card_points != 0">カード点</dt>
                <dd v-if="improvement.card.card_points != 0">{{ improvement.card.card_points }}点</dd>
            </dl>
            <p class="description">{{ improvement.card.description }}</p>
            <a :href="`https://buratsuki.work/cards/view/${improvement.card.id}`" target="_blank" rel="noopener">詳細ページを見る(ぶらつき学生ポータル)</a>
        </b-modal>
    </li>
</template>
<script>
export default {
    props: ['improvement'],
}
</script>
<style scoped>
.list-group-item-action {
    cursor: pointer;
}
.badge {
    width: 3.5rem;
    font-size: 90%;
}
.badge-modal {
    width: 3.7rem;
}
.description {
    white-space: pre-wrap;
}
</style>
