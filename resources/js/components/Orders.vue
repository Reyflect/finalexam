<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
const orders = ref([]);
const fetchOrders = () => {
    axios
        .get("/getOrders")
        .then((response) => {
            orders.value = response.data;
        })
        .catch((error) => {
            console.error("Error fetching orders:", error);
        });
};

onMounted(() => {
    fetchOrders();
});
</script>

<template>
    <Head title="Orders" />

    <div class="card">
        <div class="card-header bg-danger">
            <h1>Orders</h1>
        </div>
        <div class="card-body"></div>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reference Number</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in orders" :key="item.id">
                <td>{{ item.payment_reference_number }}</td>
                <td>{{ item.total }}</td>
                <td>{{ item.status }}</td>
                <td>{{ item.created_at }}</td>
                <td>
                    <button class="btn-flat btn-primary">Update</button>
                </td>
            </tr>
        </tbody>
    </table>
</template>
