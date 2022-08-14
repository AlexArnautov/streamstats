<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-table-rows"></i> <strong>Median Number of Viewers for top 1000</strong>
        </div>
        <div class="card-body">
            <div class="median">{{ data.median_viewers }}</div>
        </div>
    </div>
</template>
<script>
import api from '../api';

export default {
    data() {
        return {
            data: [],
        };
    },
    async mounted() {
        this.data = await api.helpGet('stream/median-viewers');
    }
};
</script>

<style scoped>
.median {
    text-align: center;
    font-size: xxx-large;
    text-shadow: #5a6268;
}

</style>