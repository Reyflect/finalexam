<template>
    <div>
        <CategoryForm
            :category="selectedCategory"
            :isEditing="isEditing"
            @submit="handleSubmit"
        />
    </div>
</template>

<script>
import CategoryForm from "@/components/CategoryForm.vue";
import axios from "axios";
export default {
    props: {
        category: {
            type: Object,
            default: () => ({
                name: "",
                description: "",
                image: [],
            }),
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        CategoryForm,
    },
    data() {
        return {
            selectedCategory: this.category,
            isEditing: this.isEditing,
        };
    },
    methods: {
        handleSubmit() {
            if (this.isEditing) {
                console.log("editing");
            }
        },
    },
};
</script>
