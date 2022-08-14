<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-table-list"></i> <strong>Top Games by Viewer Count</strong>
        </div>
        <div class="card-body">
            <a-table
                :dataSource='data'
                :columns='columns'
                rowKey='game'
                size="small"
            >
            </a-table>
        </div>
    </div>
</template>
<script>
import api from '../api';

export default {
    data() {
        return {
            data: [],
            columns: [
                {
                    title: 'Game',
                    dataIndex: 'game',
                    key: 'game',
                },
                {
                    title: 'Viewers',
                    dataIndex: 'viewers',
                    key: 'viewers',
                },
            ]
        };
    },
    async mounted() {
        this.data = await api.helpGet('game/top-games');
    }
};
</script>