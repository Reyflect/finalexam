<template>
    <nav>
        <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <a
                    class="page-link"
                    @click="changePage(currentPage - 1)"
                    aria-label="Previous"
                >
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li
                v-for="page in totalPages"
                :key="page"
                class="page-item"
                :class="{ active: currentPage === page }"
            >
                <a class="page-link" @click="changePage(page)">{{ page }}</a>
            </li>
            <li
                :class="{ disabled: currentPage === totalPages }"
                class="page-item"
            >
                <a
                    class="page-link"
                    @click="changePage(currentPage + 1)"
                    aria-label="Next"
                >
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
        <div class="pagination-info">
            <p>Page {{ currentPage }} of {{ totalPages }}</p>
        </div>
    </nav>
</template>

<script setup>
import { ref, watch, computed, defineProps, defineEmits, onMounted } from "vue";

const props = defineProps(["totalItems", "perPage", "initialPage"]);
const emits = defineEmits(["page-change"]);

const currentPage = ref(props.initialPage);

const totalPages = computed(() => Math.ceil(props.totalItems / props.perPage));

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        emits("page-change", currentPage.value);
    }
};

watch(
    () => props.totalItems,
    () => {
        if (currentPage.value > totalPages.value) {
            currentPage.value = totalPages.value;
            emits("page-change", currentPage.value);
        }
    }
);

onMounted(() => {
    emits("page-change", currentPage.value);
});
</script>

<style scoped>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination-info {
    text-align: center;
    margin-top: 10px;
}
</style>
