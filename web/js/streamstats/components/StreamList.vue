<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-table-list"></i> <strong>Top 100 Streams by Viewer Count</strong>
        </div>
        <div class="card-body">
            <a-table
                :dataSource='streams'
                :columns='columns'
                rowKey='id'
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
                    title: 'Channel',
                    dataIndex: 'channel_name',
                    key: 'channel_name',
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
                    sorter: (a, b) => a.viewer_count - b.viewer_count,
                    sortDirections: ['descend', 'ascend'],
                },
            ]
        };
    },
    async mounted() {
        this.streams = await api.helpGet('stream/top');
    }
};
</script>