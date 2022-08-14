<template>
    <h1>Common Data</h1>
    <div class="row">
        <div class="col-12">
            <StreamList/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-6">
            <TopGamesBeViewerList/>
        </div>
        <div class="col-6">
            <NumberOfStreamsForGameList/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-8">
            <StreamsByHours/>
        </div>
        <div class="col-4">
            <MedianNumberOfViewers/>
        </div>
    </div>
    <h2 class="mt-2">User Data</h2>
    <div class="row mt-3">
        <div class="col-12">
            <UserFollowsFromTop/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-8">
            <SharedTags/>
        </div>
        <div class="col-4">
            <UserLowestStream/>
        </div>
    </div>
</template>

<script>
import StreamList from './components/StreamList.vue';
import TopGamesBeViewerList from './components/TopGamesByViewerList.vue';
import NumberOfStreamsForGameList from './components/NumberOfStreamsForGameList.vue';
import StreamsByHours from './components/StreamsByHours.vue';
import MedianNumberOfViewers from './components/MedianNumberOfViewers.vue';
import UserFollowsFromTop from './components/UserFollowsFromTop.vue';
import UserLowestStream from "./components/UserLowestStream.vue";
import SharedTags from "./components/SharedTags.vue";

export default {
    components: {
        StreamList,
        TopGamesBeViewerList,
        NumberOfStreamsForGameList,
        StreamsByHours,
        MedianNumberOfViewers,
        UserFollowsFromTop,
        UserLowestStream,
        SharedTags,
    }
}
</script>

<style scoped>
</style>