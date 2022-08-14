<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-user"></i> <strong>Lower User Followed Stream</strong>
        </div>
        <div class="card-body">
            <strong>Lower User Stream:</strong> {{ data.title }}<br/>
            <strong>Need Viewers for top 1000:</strong> {{ data.need_viewers_for_top }}
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
        this.data = await api.helpGet('user/lower-user-stream');
    }
};
</script>

<style scoped>

</style>