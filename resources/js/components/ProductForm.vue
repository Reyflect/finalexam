<template>
    <div>
        <!-- Step 1 -->
        <div v-if="step === 1">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Step 1</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input
                            v-model="formData.name"
                            type="text"
                            name="name"
                            placeholder="Name"
                            class="form-control"
                            maxlength="255"
                        />
                        <span v-show="validateName()" class="text-danger"
                            >Please enter a valid name.</span
                        >
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input
                            v-model="formData.price"
                            type="number"
                            name="Price"
                            placeholder="price"
                            class="form-control"
                            min="1"
                            required
                        />
                        <span v-show="validatePrice()" class="text-danger"
                            >Please set a price.</span
                        >
                    </div>

                    <div class="form-group">
                        <label for="price">Stock:</label>
                        <input
                            v-model="formData.stock"
                            type="number"
                            name="stock"
                            placeholder="stock"
                            class="form-control"
                            min="1"
                            required
                        />
                        <span v-show="validateStock()" class="text-danger"
                            >Please set a stock.</span
                        >
                    </div>
                    <div class="form-group">
                        <label for="category">Category: </label>
                        <select
                            v-model="formData.category"
                            name="category"
                            placeholder="Category"
                            class="form-control"
                        >
                            <option value="">Select Category</option>
                            <option
                                v-for="category in categoryOptions"
                                :key="category"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <span v-show="validateCategory()" class="text-danger"
                            >Please select a category.</span
                        >
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea
                            v-model="formData.description"
                            name="description"
                            placeholder="Description"
                            class="form-control"
                        ></textarea>
                        <span v-show="validateDescription()" class="text-danger"
                            >Please enter a description.</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div v-if="step === 2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Step 2</h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="images">Images:</label>
                        <input
                            type="file"
                            name="images[]"
                            multiple
                            v-on:change="handleFileChange"
                        />
                        <span v-show="!validateImages()" class="text-danger"
                            >Please select at least one image. File size should
                            be less than 2 MB. Accepted image types are .jpg,
                            .jpeg, .png
                            <br />
                            {{
                                isEditing
                                    ? "Editing this will replace the existing image"
                                    : ""
                            }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div v-if="step === 3">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Step 3</h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="datetime">Date and Time:</label>
                        <input
                            type="datetime-local"
                            v-model="formData.datetime"
                            name="datetime"
                        />
                        <span v-show="!validateDatetime()" class="text-danger">
                            Please enter a valid date and time.</span
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation buttons -->
        <button
            @click.prevent="prevStep"
            v-if="step > 1"
            class="btn btn-danger"
        >
            Previous
        </button>
        <button
            @click.prevent="nextStep"
            v-if="step < totalSteps"
            class="btn btn-primary"
        >
            Next
        </button>

        <button
            @click.prevent="submitForm"
            v-if="step === totalSteps"
            class="btn btn-success"
        >
            {{ isEditing ? "Update Product" : "Add Product" }}
        </button>
    </div>
</template>

<script>
import axios from "axios";
// Create a new Axios instance with custom configuration
const axiosInstance = axios.create({
    baseURL: "/dashboard",
    maxRedirects: 5, // Set the maximum number of redirects
});

export default {
    props: {
        product: {
            type: Object,
            default: () => ({
                name: "",
                category: "",
                price: "",
                stock: "",
                description: "",
                images: [],
                datetime: "",
            }),
        },
        isEditing: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            step: 1,
            totalSteps: 3,
            formData: {
                name: this.product.name,
                category: this.product.category,
                description: this.product.description,
                stock: this.product.stock,
                images: [],
                datetime: this.product.datetime,
                price: this.product.price,
            },
            categoryOptions: [],
            validImage: false,
            field_name: false,
            field_price: false,
            field_desc: false,
            field_category: false,
            field_stock: false,
        };
    },
    watch: {
        product: {
            handler(newVal) {
                this.formData = {
                    name: newVal.name,
                    category: newVal.category_id,
                    description: newVal.description,
                    images: [],
                    datetime: newVal.datetime,
                    price: newVal.price,
                    stock: newVal.stock,
                };
            },
            deep: true,
        },
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        nextStep() {
            this.step++;
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
            }
        },
        validateName() {
            return this.field_name;
        },
        validatePrice() {
            return this.field_price;
        },
        validateCategory() {
            return this.field_category;
        },
        validateDescription() {
            return this.field_desc;
        },
        validateStock() {
            return this.field_stock;
        },
        validateImages() {
            if (this.isEditing && this.validImage) {
                return true;
            }
            return this.formData.images.length > 0 && this.validImage;
        },
        validateDatetime() {
            return this.formData.datetime !== "";
        },
        handleFileChange(event) {
            const selectedFiles = event.target.files;
            const allowedExtensions = ["jpg", "jpeg", "png"];

            // Filter files to include only JPG and PNG images
            const validFiles = Array.from(selectedFiles).filter((file) => {
                const extension = file.name.split(".").pop().toLowerCase();
                return allowedExtensions.includes(extension);
            });

            if (validFiles.length === selectedFiles.length) {
                this.formData.images = event.target.files;
                this.validImage = true;
            } else {
                this.validImage = false;
            }
        },
        fetchCategories() {
            axios
                .get("/getDistinctCategories")
                .then((response) => {
                    this.categoryOptions = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching categories:", error);
                });
        },
        submitForm() {
            const formData = new FormData();
            formData.append("name", this.formData.name);
            formData.append("category", this.formData.category);
            formData.append("description", this.formData.description);
            formData.append("datetime", this.formData.datetime);
            formData.append("price", this.formData.price);
            formData.append("stock", this.formData.stock);
            for (const image of this.formData.images) {
                formData.append("images[]", image);
            }

            const endpoint = this.isEditing
                ? `/api/updateproduct/${this.product.id}`
                : "/api/addnewproduct";

            axios
                .post(endpoint, formData)
                .then((response) => {
                    const createdProduct = response.data;
                    this.$emit("create-submit", createdProduct);
                    // console.log(response.data.redirect_url);
                    window.location = response.data.redirect_url;
                })
                .catch((error) => {
                    console.error("Error submitting form:", error);
                    alert("Please Check The form");
                    this.handleErrors(JSON.parse(error.request.response));
                });
        },
        handleErrors(validation_error) {
            // console.log(validation_error.form_error);

            if (validation_error.form_error.name != null) {
                this.field_name = true;
            }
            if (validation_error.form_error.description != null) {
                this.field_desc = true;
            }
            if (validation_error.form_error.category != null) {
                this.field_category = true;
            }
            if (validation_error.form_error.price != null) {
                this.field_price = true;
            }
            if (validation_error.form_error.stock != null) {
                this.field_stock = true;
            }
        },
    },
};
</script>
