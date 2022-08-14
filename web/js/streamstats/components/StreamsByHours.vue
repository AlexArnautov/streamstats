<template>
    <div class="card">
        <div class="card-header">
            <i class="fa-solid fa-table-list"></i> <strong>Total number of streams by Hours</strong>
        </div>
        <div class="card-body">
            <a-table
                :dataSource='data'
                :columns='columns'
                rowKey='streams'
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
                    title: 'Month',
                    dataIndex: 'month',
                    key: 'month',
                },
                {
                    title: 'Day',
                    dataIndex: 'day',
                    key: 'day',
                },
                {
                    title: 'Hour',
                    dataIndex: 'hour',
                    key: 'hour',
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
        this.data = await api.helpGet('stream/streams-by-hour');
    }
};
</script>