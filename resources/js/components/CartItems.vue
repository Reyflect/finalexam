<template>
    <div class="card">
        <div class="card-header">
            <h2>Your Cart</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                <tr v-for="item in cartItems" :key="item.id">
                    <td>{{ item.product.name }}</td>
                    <td>
                        <input
                            min="1"
                            type="number"
                            v-model="item.quantity"
                            @input="updateQuantity(item)"
                        />
                    </td>
                    <td>
                        <button
                            class="btn-flat btn-danger"
                            @click="removeItem(item.id)"
                        >
                            Remove
                        </button>
                    </td>
                </tr>
            </table>
            <p v-show="isCartEmpty">No items in cart yet</p>
            <div style="margin-top: 1%">
                <a
                    class="btn btn-primary"
                    href="/checkout"
                    v-show="!isCartEmpty"
                    >Checkout</a
                >
            </div>
        </div>
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
        isCartEmpty() {
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
