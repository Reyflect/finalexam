<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products List</h3>
                    <div class="card-tools">
                        <div
                            class="input-group input-group-sm"
                            style="width: 300px"
                        >
                            <input
                                v-model="searchTerm"
                                type="text"
                                name="table_search"
                                class="form-control float-right"
                                placeholder="Search"
                            />
                            <select
                                v-model="selectedCategory"
                                class="form-control"
                            >
                                <option value="">All Categories</option>
                                <option
                                    v-for="category in uniqueCategories"
                                    :key="category"
                                    :value="category"
                                >
                                    {{ category }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div v-for="product in paginatedProducts" :key="product.id">
                    <div class="row">
                        <!-- Product Information -->
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="m-0">
                                        Product Name: {{ product.name }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div
                                        v-for="(image, index) in JSON.parse(
                                            product.images
                                        )"
                                        :key="index"
                                    >
                                        <img
                                            :src="baseImageDirectory + image"
                                            alt="Product Image"
                                            class="img-fluid mb-2"
                                            :style="{
                                                width: '100px',
                                                height: '100px',
                                            }"
                                        />
                                    </div>

                                    <h6 class="card-title">
                                        Category: {{ product.category }}
                                    </h6>
                                    <p class="card-text">
                                        Description: {{ product.description }}
                                        <br />
                                        Date added/updated:
                                        {{ formatDateTime(product.datetime) }}
                                    </p>
                                    <a
                                        class="btn btn-primary"
                                        v-if="product.id"
                                        :href="'/editproduct/' + product.id"
                                        >EDIT</a
                                    >
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="btn btn-danger"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <Pagination
                    :total-items="filteredProducts.length"
                    v-if="filteredProducts.length > 0"
                    :per-page="itemsPerPage"
                    :initial-page="currentPage"
                    @page-change="paginate"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watchEffect } from "vue";
import axios from "axios";
import Pagination from "./Pagination.vue";

const products = ref([]);
const searchTerm = ref("");
const currentPage = ref(1);
const itemsPerPage = 5;
const baseImageDirectory = "http://localhost:8000/storage/";
const totalPages = computed(() =>
    Math.ceil(filteredProducts.length / itemsPerPage)
);

const getProducts = () => {
    axios.get("/api/products").then((response) => {
        products.value = response.data;
        searchProducts();
    });
};

const filteredProducts = computed(() => {
    const lowerSearchTerm = searchTerm.value.toLowerCase();
    return products.value.filter((product) => {
        const lowerProductName = product.name.toLowerCase();
        const lowerProductDescription = product.description.toLowerCase();
        const matchesSearch =
            !lowerSearchTerm ||
            lowerProductName.includes(lowerSearchTerm) ||
            lowerProductDescription.includes(lowerSearchTerm);

        const matchesCategory =
            !selectedCategory.value ||
            product.category === selectedCategory.value;

        return matchesSearch && matchesCategory;
    });
});

const searchProducts = () => {
    const lowerSearchTerm = searchTerm.value.toLowerCase();
    const filteredByCategory = selectedCategory.value
        ? filteredProducts.value.filter(
              (product) => product.category === selectedCategory.value
          )
        : filteredProducts.value;

    if (!lowerSearchTerm && !selectedCategory.value) {
        filteredProducts.value = [...products.value];
    } else {
        filteredProducts.value = filteredByCategory.filter((product) => {
            const lowerProductName = product.name.toLowerCase();
            const lowerProductDescription = product.description.toLowerCase();
            return (
                !lowerSearchTerm ||
                lowerProductName.includes(lowerSearchTerm) ||
                lowerProductDescription.includes(lowerSearchTerm)
            );
        });
    }
};

const paginatedProducts = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return filteredProducts.value.slice(startIndex, endIndex);
});

const paginate = (page) => {
    currentPage.value = page;
};
onMounted(() => {
    getProducts();
    currentPage.value = parseQueryParameters();
    goToPage(currentPage.value);
    paginate(currentPage.value);
});

const selectedCategory = ref("");

const getUniqueCategories = () => {
    const categories = new Set(
        products.value.map((product) => product.category)
    );
    return Array.from(categories);
};

const uniqueCategories = computed(() => getUniqueCategories());

const formatDateTime = (dateTimeString) => {
    if (dateTimeString === null) {
        return "none";
    }
    const options = {
        year: "numeric",
        month: "numeric",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        hour12: true,
    };

    return new Date(dateTimeString).toLocaleString(undefined, options);
};

const deleteProduct = (productId) => {
    if (window.confirm("Are you sure you want to delete this product?")) {
        axios
            .delete(`/api/products/${productId}`)
            .then(() => {
                products.value = products.value.filter(
                    (product) => product.id !== productId
                );
            })
            .catch((error) => {
                console.error("Error deleting product:", error);
            });
    }
};
const parseQueryParameters = () => {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const page = urlSearchParams.get("page");

    return page ? parseInt(page) : 1;
};

const goToPageNumber = ref(1);

const goToPage = () => {
    const pageNumber = parseInt(goToPageNumber.value);
    if (pageNumber >= 1 && pageNumber <= totalPages.value) {
        currentPage.value = pageNumber;
        emits("page-change", currentPage.value);
    }
};

watchEffect(() => {
    currentPage.value = parseQueryParameters();
});
</script>
