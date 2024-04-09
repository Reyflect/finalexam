<template>
    <div class="card">
        <div class="card-header">
            <h2>Checkout</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
                <tr v-for="item in cartItems" :key="item.id">
                    <td>{{ item.product.name }}</td>
                    <td>P{{ item.product.price }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.product.price * item.quantity }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total Amount: P{{ total }}</td>
                </tr>
            </table>

            <form ref="paymentForm" action="/api/maya" method="POST">
                <input
                    type="hidden"
                    name="cartItems"
                    :value="JSON.stringify(cartItems)"
                />
                <input type="hidden" name="total_price" :value="total" />

                <div style="margin-top: 1%">
                    <button
                        class="btn btn-success"
                        type="submit"
                        @click="processPayment"
                        v-show="!isCartEmpty"
                    >
                        Pay With Maya
                    </button>
                </div>
            </form>

            <p v-show="isCartEmpty">No items in cart!</p>
        </div>
    </div>
</template>

<script type="ts">
import { ref } from "vue";
export default {
    props: {
        cartItems: {
            type: Array,
            required: true,
        },
    },
    computed: {
        total() {
            return this.cartItems.reduce((total, item) => {
                return total + item.product.price * item.quantity;
            }, 0);
        },
        cartContents() {
            return JSON.stringify(cartItems.value);
        },

        isCartEmpty() {
            return this.cartItems.length === 0;
        },

    },
    setup(props) {
        const cartItems = ref(props.cartItems); // Create a reactive ref

        const processPayment = () => {
            // Submit the payment form
            const paymentForm = document.getElementById("paymentForm");
            paymentForm.submit();
        };
        return { cartItems, processPayment };
    },
};
</script>
