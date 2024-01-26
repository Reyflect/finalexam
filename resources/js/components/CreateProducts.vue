<template>
    <div>
        <product-form :formData="formData" @create-submit="submitCreateForm" />
    </div>
</template>

<script>
import ProductForm from "./ProductForm.vue";

export default {
    components: {
        ProductForm,
    },
};
</script>
