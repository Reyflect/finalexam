<script setup>
import { Head } from "@inertiajs/vue3";

defineProps({ content: "product" });
</script>

<script>
import ProductList from "./ListProducts.vue";
import CreateProducts from "./CreateProducts.vue";
import EditProducts from "./EditProducts.vue";
import CartItemsVue from "./CartItems.vue";
import Checkout from "./Checkout.vue";
import SuccessVue from "./Success.vue";
import FailVue from "./Fail.vue";
import VideoList from "./VideoList.vue";
import SideBar from "./Sidebar.vue";
import Orders from "./Orders.vue";
import Category from "./Category.vue";

import CategoryAddUpdate from "./CategoryAddUpdate.vue";
export default {
    components: {
        ProductList,
        CreateProducts,
        EditProducts,
        CartItemsVue,
        Checkout,
        SuccessVue,
        FailVue,
        VideoList,
        SideBar,
        Orders,
        Category,
        CategoryAddUpdate,
    },
};
</script>
<template>
    <Head title="Dashboard" />
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        data-widget="pushmenu"
                        href="#"
                        role="button"
                        ><i class="fas fa-bars"></i
                    ></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="/dashboard" class="nav-link">Home</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form action="/logout" method="POST">
                        <input
                            type="hidden"
                            name="_token"
                            :value="$page.props.csrf_token"
                        />
                        <button class="btn btn-danger">LOGOUT</button>
                    </form>
                </li>
            </ul>
        </nav>

        <SideBar></SideBar>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2" v-if="content == 'product'">
                        <div class="col-sm-10">
                            <h1 class="m-0">Products</h1>
                            <ProductList></ProductList>
                        </div>
                        <div class="col-sm-2">
                            <ol class="breadcrumb float-sm-right">
                                <a href="/createproduct" class="btn btn-primary"
                                    >Create</a
                                >
                            </ol>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'addcategory'">
                        <div class="col-sm-12">
                            <CategoryAddUpdate
                                :isEditing="false"
                            ></CategoryAddUpdate>
                        </div>
                    </div>

                    <div class="row mb-2" v-if="content == 'editcategory'">
                        <div class="col-sm-12">
                            <CategoryAddUpdate
                                :isEditing="true"
                                :category="$attrs.category"
                            ></CategoryAddUpdate>
                        </div>
                    </div>

                    <div class="row mb-2" v-if="content == 'addproduct'">
                        <div class="col-sm-12">
                            <CreateProducts></CreateProducts>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'editProduct'">
                        <div class="col-sm-12">
                            <EditProducts
                                :product-id="$attrs.product.id"
                            ></EditProducts>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'category'">
                        <div class="col-sm-12">
                            <category></category>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'cart'">
                        <div class="col-sm-12">
                            <CartItemsVue
                                :cartItems="$attrs.cartItems"
                            ></CartItemsVue>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'checkout'">
                        <div class="col-sm-12">
                            <Checkout :cartItems="$attrs.cartItems"></Checkout>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'success'">
                        <div class="col-sm-12">
                            <SuccessVue></SuccessVue>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'fail'">
                        <div class="col-sm-12">
                            <FailVue></FailVue>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'video'">
                        <div class="col-sm-12">
                            <VideoList
                                :initial-video="$attrs.video"
                            ></VideoList>
                        </div>
                    </div>
                    <div class="row mb-2" v-if="content == 'order'">
                        <div class="col-sm-12">
                            <orders></orders>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">Back End Exam</div>

            <strong
                >Copyright &copy; 2014-2021
                <a href="https://adminlte.io">AdminLTE.io</a>.</strong
            >
            All rights reserved.
        </footer>
    </div>
</template>
