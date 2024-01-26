<template>
    <div>
        <video ref="videoPlayer" class="video-js"></video>
    </div>
</template>

<script>
import videojs from "video.js";
import "video.js/dist/video-js.css";

export default {
    props: {
        videoUrl: {
            type: String,
            required: true,
        },
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
        updateVideoSource(newVideo) {
            console.log("Updating video source with:", newVideo);
            if (this.player) {
                const fullVideoUrl = `${newVideo}`;
                // Update video source
                this.player.src({
                    src: `http://localhost:8000/storage/videos/${fullVideoUrl}.mp4`,
                    type: "video/mp4",
                });

                // Update URL dynamically
                const url = new URL(window.location.href);
                url.searchParams.set("video", newVideo);
                window.history.pushState({}, "", url.toString());
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
