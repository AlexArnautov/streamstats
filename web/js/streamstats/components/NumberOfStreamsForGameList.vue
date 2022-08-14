<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-table-rows"></i> <strong>Number of Streams for Each Game</strong>
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
                    title: 'Streams',
                    dataIndex: 'streams',
                    key: 'streams',
                },
            ]
        };
    },
    async mounted() {
        this.data = await api.helpGet('game/number-of-streams');
    }
};
</script>