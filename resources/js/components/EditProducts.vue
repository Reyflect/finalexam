<template>
    <div>
        <form
            @submit.prevent="submitForm"
            action="/api/updateproduct/${this.product.id}"
            method="POST"
        >
            <edit-product-form
                :product="selectedProduct"
                :is-editing="true"
                @update-submit="handleUpdateSubmit"
            />
        </form>
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
                price: "", // Add price and stock fields if needed
                stock: "",
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
        submitForm() {},
        handleUpdateSubmit(updatedProduct) {
            // Handle the updated product data if needed
            console.log("Product updated:", updatedProduct);
        },
    },
};
</script>
