<template>
    <Head title="Categories Form" />
    <div>
        <h2>Add Category</h2>
        <form action="/test" method="POST">
            <input
                type="hidden"
                name="_token"
                :value="$page.props.csrf_token"
            />
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="category.name" required />
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea
                    id="description"
                    v-model="category.description"
                    required
                ></textarea>
            </div>
            <div>
                <label for="image">Image:</label>
                <input
                    type="file"
                    id="image"
                    @change="handleImageChange"
                    accept="image/*"
                />
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            category: {
                name: "",
                description: "",
                image: null,
            },
        };
    },
    methods: {
        handleImageChange(event) {
            const file = event.target.files[0];
            if (file) {
                // You can handle file processing here, such as previewing the image
                console.log(file);
                this.category.image = file;
            }
        },
    },
};
</script>
