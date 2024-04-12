<template>
    <div class="row">
        <input
            type="number"
            v-model="quantity"
            min="1"
            placeholder="Quantity"
            class="col-3"
        />

        <button class="btn-flat btn-primary" @click="addToCart">
            Add to Cart
        </button>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    props: ["productId", "productname", "stock"],
    setup(props) {
        const quantity = ref(1); // Default quantity

        // Function to get the user ID
        const getUserId = async () => {
            try {
                const response = await axios.get("/getusers");
                console.log(response.data);
                return response.data; // Assuming the API response contains the user_id field
            } catch (error) {
                console.error("Error getting user ID:", error);
                return null;
            }
        };

        // Function to add item to cart
        const addToCart = async () => {
            const userId = await getUserId(); // Get the user ID

            if (!userId) {
                alert("Failed to get user ID. Please try again.");
                return;
            }

            try {
                const response = await axios.post("/api/cart/add", {
                    productId: props.productId,
                    quantity: quantity.value,
                    stock: props.stock,
                    user_id: userId,
                });
                window.alert(
                    "Added " +
                        quantity.value +
                        " " +
                        props.productname +
                        " to cart"
                );
            } catch (error) {
                console.error("Error adding to cart:", error);
                alert("Failed to add item to cart. Please try again.");
            }
        };

        return { quantity, addToCart };
    },
};
</script>
