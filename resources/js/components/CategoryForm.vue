<template>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                {{ isEditing ? "Edit Category" : "Add Category" }}
            </h3>
        </div>

        <div class="card-body">
            <form
                :action="formAction"
                method="POST"
                @submit.prevent="handleSubmit"
            >
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input
                        v-model="formData.name"
                        type="text"
                        name="name"
                        placeholder="Name"
                        class="form-control"
                        maxlength="255"
                    />
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea
                        v-model="formData.description"
                        name="description"
                        placeholder="Description"
                        class="form-control"
                    ></textarea>
                </div>
                <div class="form-group">
                    <label for="images">Images:</label>
                    <input
                        type="file"
                        name="images"
                        @change="handleImageChange"
                    />
                </div>
                <button type="submit" class="btn btn-primary">
                    {{ isEditing ? "Update" : "Add" }} Category
                </button>
            </form>
        </div>
    </div>
</template>

<script>
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
    data() {
        return {
            formData: {
                name: this.category.name || "",
                description: this.category.description || "",
                image: [],
            },
        };
    },
    computed: {
        formAction() {
            // Generate the correct action URL based on isEditing
            return this.isEditing
                ? `/category/edit/${this.category.id}`
                : "/category/add";
        },
    },
    methods: {
        handleSubmit() {
            if (this.isEditing) {
                this.$emit("update-category", this.formData);
            } else {
                this.$emit("add-category", this.formData);
            }
        },
        handleImageChange(event) {
            // Update formData.image when an image is selected
            const selectedFile = event.target.files[0];
            this.formData.image = selectedFile;
        },
    },
};
</script>
