<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-user"></i> <strong>User Shared Tags with Top 1000</strong>
        </div>
        <div class="card-body">
            <li v-for="value in data">
                {{ value }}
            </li>
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
        this.data = await api.helpGet('user/shared-tags');
    }
};
</script>

<style scoped>

</style>