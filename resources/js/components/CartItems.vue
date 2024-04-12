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
                        <th>Stock Left</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in cartItems" :key="item.id">
                        <td>{{ item.product.name }}</td>
                        <td>{{ item.product.stock }}</td>
                        <td>
                            <input
                                min="1"
                                type="number"
                                v-model="item.quantity"
                            />
                            <button @click="updateQuantity(item)">
                                Update
                            </button>
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
import { ref, onMounted } from "vue";
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
        //  const cartItems = ref(props.cartItems);
        const cartItems = ref([]);

        // Function to fetch cart items
        const fetchCartItems = async () => {
            try {
                const response = await axios.get("/items"); // Modify the endpoint as needed
                cartItems.value = response.data;
                console.log(response);
            } catch (error) {
                console.error("Error fetching cart items:", error);
            }
        };

        onMounted(() => {
            fetchCartItems(); // Fetch cart items after component is mounted
        });
        //gets user id
        const getUserId = async () => {
            try {
                const response = await axios.get("/getusers");

                return response.data; // Assuming the API response contains the user_id field
            } catch (error) {
                console.error("Error getting user ID:", error);
                return null;
            }
        };

        //update quantity of the field
        const updateQuantity = async (item) => {
            if (item.quantity !== "") {
                if (item.quantity < 1) {
                    // Set quantity to 1 if it becomes negative
                    item.quantity = 1;
                }
                try {
                    const userId = await getUserId(); // Get the user ID

                    if (!userId) {
                        alert("Failed to get user ID. Please try again.");
                        return;
                    }

                    const response = await axios
                        .put(`/api/cart/update/${item.id}`, {
                            quantity: item.quantity,
                            product_id: item.product.id,
                            item_stock: item.product.stock,
                            user_id: userId,
                        })
                        .then(() => {
                            fetchCartItems();
                        });
                } catch (error) {
                    alert("not enough stock");
                    item.quantity = item.quantity;
                }
            }
        };

        //removes an item in the cart
        const removeItem = async (itemId) => {
            try {
                const userId = await getUserId(); // Get the user ID

                if (!userId) {
                    alert("Failed to get user ID. Please try again.");
                    return;
                }

                await axios.delete(`/api/cart/remove/${itemId}`, {
                    params: {
                        user_id: userId,
                    },
                });
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
