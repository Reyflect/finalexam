<template>
    <div>
        <form
            @submit.prevent="submitForm"
            action="/api/addnewproduct"
            method="POST"
        >
            <product-form
                :form-data="formData"
                @create-submit="handleCreateSubmit"
            />
            <!--button type="submit">Submit</button-->
        </form>
    </div>
</template>

<script>
import ProductForm from "./ProductForm.vue";

export default {
    components: {
        ProductForm,
    },
    data() {
        return {
            formData: {
                name: "",
                category: "",
                price: "",
                stock: "",
                description: "",
                images: [],
                datetime: "",
            },
        };
    },
    methods: {
        submitForm() {},
        handleCreateSubmit(createdProduct) {},
    },
};
</script>
