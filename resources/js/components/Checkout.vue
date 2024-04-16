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

            <form ref="paymentForm" method="POST" action="api/maya">
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

            <!--p v-show="isCartEmpty">No items in cart!</p-->
        </div>
    </div>
</template>

<script type="ts">
import { ref ,onMounted } from "vue";
import axios from "axios";

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
      /*  var responseData = ref([]);
        // Function to fetch cart items
            const fetchTotal = async () => {
                try {
                    const response = await axios.get("/totalItems"); // Modify the endpoint as needed


                    return parseInt(response.data);

                } catch (error) {
                    console.error("Error fetching cart items:", error);
                }
            };

          // Function to fetch cart items
        const fetchCheckoutDetails = async () => {
            try {
                const formData = new FormData();
                const totalItems = await fetchTotal();
                formData.append("cartItems", JSON.stringify(cartItems.value));
                formData.append("total_price",totalItems);

                const response = await axios.post(`/api/maya`, formData);

                // Assuming the response contains JSON data, you can access it like this:
                return response.data;
                // You can do further processing or update your component state with the fetched data here
            } catch (error) {
                console.error("Error fetching cart items:", error);
            }
        };

        // Use the onMounted hook to trigger fetching cart items after the component is mounted
        onMounted(async () => {
            responseData.value = await fetchCheckoutDetails();
        });
        const processPayment = () => {
            const formData = new FormData();
            formData.append("checkOutId", JSON.stringify(responseData.value.checkoutId));
            window.location.href = responseData.value.redirectUrl;


        };*/

        return { cartItems };
    },
};
</script>
