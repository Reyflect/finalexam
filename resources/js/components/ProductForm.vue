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
                        />
                        <span v-show="!validateName()" class="text-danger"
                            >Please enter a valid name.</span
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">Category:</label>
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
                                :value="category"
                            >
                                {{ category }}
                            </option>
                        </select>
                        <span v-show="!validateCategory()" class="text-danger"
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
                        <span
                            v-show="!validateDescription()"
                            class="text-danger"
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
                            >Please select at least one image. Accepted types
                            are .jpg, .jpeg, .png
                        </span>
                        <span>
                            {{
                                isEditing
                                    ? "Editing this will replace the existing image"
                                    : ""
                            }}</span
                        >
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
            :disabled="!validateForm()"
            class="btn btn-primary"
        >
            Next
        </button>
        <button
            @click.prevent="submitForm"
            v-if="step === totalSteps"
            :disabled="!validateForm()"
            class="btn btn-success"
        >
            {{ isEditing ? "Update Product" : "Add Product" }}
        </button>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        product: {
            type: Object,
            default: () => ({
                name: "",
                category: "",
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
                images: [],
                datetime: this.product.datetime,
            },
            categoryOptions: [],
            validImage: false,
        };
    },
    watch: {
        product: {
            handler(newVal) {
                this.formData = {
                    name: newVal.name,
                    category: newVal.category,
                    description: newVal.description,
                    images: [],
                    datetime: newVal.datetime,
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
            if (this.step === 1) {
                if (
                    this.validateName() &&
                    this.validateCategory() &&
                    this.validateDescription()
                ) {
                    this.step++;
                }
            } else if (this.step === 2) {
                if (this.validateImages()) {
                    this.step++;
                }
            } else if (this.step < this.totalSteps) {
                this.step++;
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--;
            }
        },
        validateName() {
            return this.formData.name.trim() !== "";
        },
        validateCategory() {
            return this.formData.category !== "";
        },
        validateDescription() {
            return this.formData.description.trim() !== "";
        },
        validateImages() {
            if (this.isEditing && this.validImage) {
                return true;
            }
            return this.formData.images.length > 0;
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
                console.error("VALID");
                this.formData.images = event.target.files;
                this.validImage = true;
            } else {
                console.error("Please select only JPG or PNG images.");
                this.validImage = false;
            }
        },
        validateForm() {
            switch (this.step) {
                case 1:
                    return (
                        this.validateName() &&
                        this.validateCategory() &&
                        this.validateDescription()
                    );
                case 2:
                    return this.validateImages();
                case 3:
                    return this.validateDatetime();
                default:
                    return false;
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
            if (this.validateForm()) {
                const formData = new FormData();
                formData.append("name", this.formData.name);
                formData.append("category", this.formData.category);
                formData.append("description", this.formData.description);
                formData.append("datetime", this.formData.datetime);

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

                        if (response.data.redirect_url) {
                            window.location.href = response.data.redirect_url;
                        }
                    })
                    .catch((error) => {
                        console.error("Error submitting form:", error);
                    });
            }
        },
    },
};
</script>
