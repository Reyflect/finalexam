<template>
    <div>
        <h2>Final Checkout</h2>
        <ul>
            <li v-for="item in cartItems" :key="item.id">
                {{ item.product.name }} (P{{ item.product.price }}) - Quantity:
                {{ item.quantity }}

                Subtotal: {{ item.product.price * item.quantity }}
            </li>
        </ul>
        <p>Total Amount: P{{ total }}</p>

        <form ref="paymentForm" action="/api/maya" method="POST">
            <input
                type="hidden"
                name="cartItems"
                :value="JSON.stringify(cartItems)"
            />
            <input type="hidden" name="total_price" :value="total" />

            <button
                class="btn btn-success"
                type="submit"
                @click="processPayment"
                v-show="!isCartEmpty"
            >
                Pay With Maya
            </button>
        </form>

        <p v-show="isCartEmpty">No items in cart!</p>
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
