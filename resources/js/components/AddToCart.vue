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
    props: ["productId"],
    setup(props) {
        const quantity = ref(1); // Default quantity
        const addToCart = async () => {
            try {
                const response = await axios.post("/api/cart/add", {
                    productId: props.productId,
                    quantity: quantity.value,
                });
                console.log(response.data);
                window.alert("Item added to cart");
            } catch (error) {
                console.error(error); // Handle error
            }
        };

        return { quantity, addToCart };
    },
};
</script>
