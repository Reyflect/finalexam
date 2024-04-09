<template>
    <div>
        <h2>Your Cart</h2>
        <ul>
            <li v-for="item in cartItems" :key="item.id">
                {{ item.product.name }} (P{{ item.product.price }}) - Quantity:
                <input
                    min="1"
                    type="number"
                    v-model="item.quantity"
                    @input="updateQuantity(item)"
                />
                <button
                    class="btn-flat btn-danger"
                    @click="removeItem(item.id)"
                >
                    Remove
                </button>
            </li>
        </ul>

        <a class="btn btn-primary" href="/checkout" v-show="!isPropEmpty"
            >Checkout</a
        >
        <p v-show="isPropEmpty">No items in cart yet</p>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    props: {
        cartItems: {
            type: Array,
            required: true,
        },
    },
    computed: {
        isPropEmpty() {
            // Check if the prop is empty
            return this.cartItems.length === 0;
        },
    },
    setup(props) {
        const cartItems = ref(props.cartItems); // Create a reactive ref

        const updateQuantity = async (item) => {
            try {
                const response = await axios.put(
                    `/api/cart/update/${item.id}`,
                    { quantity: item.quantity }
                );
                // Update the quantity in the local cartItems array based on server response
                const updatedItem = response.data.cartItem;
                const index = cartItems.value.findIndex(
                    (id) => i.id === updatedItem.id
                );
                if (index !== -1) {
                    cartItems.value.splice(index, 1, updatedItem); // Replace old item with updated item
                }
            } catch (error) {
                console.error(error);
            }
        };

        const removeItem = async (itemId) => {
            try {
                await axios.delete(`/api/cart/remove/${itemId}`);
                cartItems.value = cartItems.value.filter(
                    (item) => item.id !== itemId
                );
            } catch (error) {
                console.error(error);
            }
        };

        return { cartItems, updateQuantity, removeItem };
    },
};
</script>
