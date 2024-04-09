<template>
    <div>
        <edit-product-form :product="selectedProduct" :is-editing="true" />
    </div>
</template>

<script>
import EditProductForm from "./ProductForm.vue";

import axios from "axios";

export default {
    components: {
        EditProductForm,
    },
    props: {
        productId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            selectedProduct: {
                name: "",
                category: "",
                description: "",
                images: [],
                datetime: "",
            },
        };
    },
    mounted() {
        this.$nextTick(() => {
            if (this.productId) {
                this.fetchProductDetails(this.productId);
            }
        });
    },
    methods: {
        fetchProductDetails(productId) {
            axios
                .get(`/api/products/${productId}`)
                .then((response) => {
                    this.selectedProduct = response.data.product;
                })
                .catch((error) => {
                    console.error("Error fetching product details:", error);
                });
        },
    },
};
</script>
