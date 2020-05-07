<template>
    <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" @click="$bvModal.show(`occupationRowModal-${occupation.card.id}`)">
        {{ occupation.card.japanese_name }}
        <span class="badge badge-occupation text-white">{{ occupation.card.id_display }}</span>
        <b-modal :id="`occupationRowModal-${occupation.card.id}`" hide-footer>
            <template v-slot:modal-title>
                {{ occupation.card.japanese_name }} <span class="badge badge-modal badge-occupation text-white">{{ occupation.card.id_display }}</span>
            </template>
            <dl>
                <dt>デッキ</dt>
                <dd>{{ occupation.card.deck.name }}</dd>
                <dt>カテゴリー</dt>
                <dd>{{ occupation.card.category }}+</dd>
            </dl>
            <p class="description">{{ occupation.card.description }}</p>
            <a :href="`https://buratsuki.work/cards/view/${occupation.card.id}`" target="_blank" rel="noopener">詳細ページを見る(ぶらつき学生ポータル)</a>
        </b-modal>
    </li>
</template>
<script>
export default {
    props: ['occupation'],
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
