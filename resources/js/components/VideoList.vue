<template>
    <div>
        <ul>
            <li v-for="video in videoList" :key="video">
                <button class="btn-primary" @click="playVideo(video)">
                    {{ video }}
                </button>
            </li>
        </ul>
        <Video :videoUrl="selectedVideo" @play-video="playVideoHandler" />
    </div>
</template>

<script>
import Video from "./Videos.vue";

export default {
    components: {
        Video,
    },
    props: {
        initialVideo: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            videoList: ["hedgehog", "gravityfalls", "guardiantales", "requiem"],
            selectedVideo: this.initialVideo,
        };
    },
    mounted() {
        this.playVideo(this.selectedVideo);
    },
    methods: {
        playVideo(videoUrl) {
            this.selectedVideo = videoUrl;
        },
        playVideoHandler(videoUrl) {
            console.log(`Video ${videoUrl} is being played.`);
        },
    },
};
</script>
