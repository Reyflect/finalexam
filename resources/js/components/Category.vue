<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
const baseImageDirectory = "http://localhost:8000/storage/";
const categories = ref([]);
const fetchCategories = () => {
    axios
        .get("/getDistinctCategories")
        .then((response) => {
            categories.value = response.data;
        })
        .catch((error) => {
            console.error("Error fetching categories:", error);
        });
};
const deleteCategory = (categoryId) => {
    if (window.confirm("Are you sure you want to delete this category?")) {
        axios
            .delete(`/category/delete/${categoryId}`)
            .then(() => {
                fetchCategories();
            })
            .catch((error) => {
                console.error("Error deleting product:", error);
            });
    }
};
onMounted(() => {
    fetchCategories();
});
</script>

<template>
    <Head title="Categories" />

    <div class="card">
        <div class="card-header bg-primary">
            <div>
                <a
                    href="/createcategory"
                    class="btn btn-default"
                    style="float: right"
                    >Create Category</a
                >
                <h1>Categories</h1>
            </div>
        </div>
    </div>

    <div v-for="category in categories" :key="category" :value="category.id">
        <div class="row">
            <!-- Product Information -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Category: {{ category.name }}</div>
                    <div class="card-body">
                        <img
                            :src="baseImageDirectory + category.image"
                            alt="Product Image"
                            class="img-fluid mb-2"
                            :style="{
                                width: '100px',
                                height: '100px',
                            }"
                        />
                        {{ category.description }}

                        <div>
                            <a
                                class="btn btn-primary"
                                :href="'editcategory/' + category.id"
                                >EDIT</a
                            >
                            <button
                                class="btn btn-danger"
                                @click="deleteCategory(category.id)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
