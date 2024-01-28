<template>
    <div>
        <div v-if="!vidFound" class="card">
            <div class="card-body">
                <h5 class="card-title">Error: No Video Found</h5>
            </div>
        </div>
        <div>
            <video ref="videoPlayer" class="video-js"></video>
        </div>
    </div>
</template>

<script>
import videojs from "video.js";
import "video.js/dist/video-js.css";
import axios from "axios";

export default {
    props: {
        videoUrl: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            vidFound: true,
            player: null,
        };
    },
    watch: {
        videoUrl(newUrl) {
            console.log("Received new videoUrl:", newUrl);
            this.updateVideoSource(newUrl);
        },
    },
    mounted() {
        console.log("Component mounted. videoUrl:", this.videoUrl);
        this.initializeVideoPlayer();
    },
    methods: {
        initializeVideoPlayer() {
            const options = {
                controls: true,
                autoplay: false,
                preload: "auto",
                fluid: true,
            };

            this.player = videojs(this.$refs.videoPlayer, options);
            this.updateVideoSource(this.videoUrl);
        },
        async updateVideoSource(newVideo) {
            const fullVideoUrl = `${newVideo}`;
            console.log("Checking if video exists:", newVideo);

            try {
                // Check if the video URL exists
                await axios.head(
                    `http://localhost:8000/storage/videos/${fullVideoUrl}.mp4`
                );

                // Video exists, update player source
                this.player.src({
                    src: `http://localhost:8000/storage/videos/${fullVideoUrl}.mp4`,
                    type: "video/mp4",
                });

                // Update URL dynamically
                const url = new URL(window.location.href);
                url.searchParams.set("video", newVideo);
                window.history.pushState({}, "", url.toString());

                // Set vidFound to true since the video exists
                this.vidFound = true;
            } catch (error) {
                this.vidFound = false;
            }
        },
        playVideo() {
            // Emit an event when the video is clicked
            this.$emit("play-video", this.videoUrl);
        },
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
        }
    },
};
</script>
