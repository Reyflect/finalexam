<template>
    <div>
        <p>{{ customString }}</p>
    </div>
</template>

<script>
export default {
    props: {
        customString: {
            type: String,
            required: true,
        },
    },
};
</script>
