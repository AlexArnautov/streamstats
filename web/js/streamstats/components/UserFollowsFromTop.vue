<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-user"></i> <strong>User Follows from Top 1000</strong>
        </div>
        <div class="card-body">
            <a-table
                :dataSource='streams'
                :columns='columns'
                rowKey='title'
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
            streams: [],
            columns: [
                {
                    title: 'Name',
                    dataIndex: 'title',
                    key: 'title',
                    ellipsis: true,
                    width: 400
                },
                {
                    title: 'Game',
                    dataIndex: 'game_name',
                    key: 'game_name',
                },
                {
                    title: 'Started',
                    dataIndex: 'started_at',
                    key: 'started_at',
                },
                {
                    title: 'Viewers',
                    dataIndex: 'viewer_count',
                    key: 'viewer_count',
                },
            ]
        };
    },
    async mounted() {
        this.streams = await api.helpGet('user/top-following');
    }
};
</script>