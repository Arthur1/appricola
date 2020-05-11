<template>
    <div class="container">
        <h1 class="mt-4 text-primary">ゲーム作成</h1>
        <form @submit.prevent="create">
            <div class="form-group">
                <label for="form_players_number">プレイ人数</label>
                <select class="form-control" id="form_players_number" v-model="players_number" @change="flushUsers" required>
                    <option value="2">2人</option>
                    <option value="3">3人</option>
                    <option value="4">4人</option>
                    <option value="5">5人</option>
                    <option value="6">6人</option>
                </select>
            </div>
            <div class="form-group">
                <b-overlay :show="this.pools.length <= 0" rounded="sm">
                    <label for="form_pool_id">カードプール</label>
                    <select class="form-control" id="form_pool_id" v-model="pool_id" required>
                        <option v-for="pool in pools" :key="pool.id" :value="pool.id">{{ pool.name }}</option>
                    </select>
                </b-overlay>
            </div>
            <div class="form-group">
                <label for="form_cards_number">ドラフト初期枚数</label>
                <select class="form-control" id="form_cards_number" v-model="cards_number" required>
                    <option value="7">7枚</option>
                    <option value="8">8枚</option>
                    <option value="9">9枚</option>
                    <option value="10">10枚</option>
                </select>
            </div>
            <div class="form-group" v-for="n in 6" :key="n" :class="{'d-none': players_number < n}">
                <label for="form_user_1">ユーザ名({{ n }}人目)</label>
                <input type="text" :id="`form_user_${n}`" v-model="users[n - 1]" class="form-control" :disabled="players_number < n" required>
            </div>
            <button type="submit" class="btn btn-primary" :disabled="is_processing">作成する</button>
        </form>
    </div>
</template>
<script>
import Utils from '../mixins/utils'
export default {
    mixins: [Utils],
    data() {
        return {
            players_number: 2,
            pool_id: null,
            cards_number: 7,
            users: [],
            is_processing: false,
            pools: [],
        }
    },
    created() {
        axios.get('/api/pools/list').then(res => {
            this.pools = res.data
        })
    },
    mounted() {
        this.users = [this.$store.state.name]
    },
    methods: {
        create() {
            this.is_processing = true
            let payload = {
                players_number: this.players_number,
                pool_id: this.pool_id,
                cards_number: this.cards_number,
                users: this.users,
            }
            axios.post('/api/games/', payload).then(res => {
                this.$toast.success('ゲームを作成しました')
                this.$router.push('/home')
            }).catch((err) => {
                this.is_processing = false
                this.errorsToast(err)
            })
        },
        flushUsers() {
            this.users = this.users.filter(user => user)
                .filter((user, key) => key < this.players_number, this)
        }
    }
}
</script>
