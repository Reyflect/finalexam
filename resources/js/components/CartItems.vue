<template>
    <div class="card">
        <div class="card-header">
            <h2>Your Cart</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
            <p v-show="isCartEmpty">No items in cart yet</p>
            <div style="margin-top: 1%">
                <a
                    class="btn btn-primary"
                    href="/checkout"
                    v-show="!isCartEmpty"
                >
                    Checkout
                </a>
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
            return this.cartItems.length === 0;
        },
    },
    setup(props) {
        const cartItems = ref(props.cartItems);
        const updateQuantity = async (item) => {
            if (item.quantity !== "") {
                if (item.quantity < 1) {
                    // Set quantity to 1 if it becomes negative
                    item.quantity = 1;
                }
                try {
                    const response = await axios.put(
                        `/api/cart/update/${item.id}`,
                        { quantity: item.quantity }
                    );
                    const updatedItem = response.data.cartItem;
                    const index = cartItems.value.findIndex(
                        (i) => i.id === updatedItem.id
                    );
                } catch (error) {
                    console.error(error);
                }
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
