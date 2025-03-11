<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '.././Layout/Layout.vue'
import { useToast } from "@/composables/useToast";
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const { showToast } = useToast();

defineProps({
    status: String,
    type: String,
    supplies: Array,
    supply_activities: Array,
    errors: Object,
    borrowed_supply: Array
});

const isOpenCreateModal = ref(false);
const isModifyQuantityModal = ref(false);
const isOpenDeleteModal = ref(false);
const isAdd = ref(true)
const item_id = ref(null);
const searchQuery = ref('');
const borrowed_searchQuery = ref('');
const processing = ref(false);
const excess_error = ref(null);
const return_excess_error = ref(null);
const isOpenReturnModal = ref(false);
const dateRangeDrawer = ref(false);
const statusValue = ref(null);
const typeValue = ref(null);
const activity_length = ref(0);
const has_activity = ref(false);
const canFilter = ref(false);

onMounted(() => {
    statusValue.value = usePage().props.status;
    typeValue.value = usePage().props.type;
});

function filterItem() {
    router.get(route('admin.supply'),
        { status: statusValue.value, type: typeValue.value },
        {
            preserveState: false,
            replace: false,
        });
}

const filteredItems = computed(() => {
    if (searchQuery.value == "") return usePage().props.supplies;
    return usePage().props.supplies.filter(supply => supply.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || supply.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const borrowed_filteredItems = computed(() => {
    if (borrowed_searchQuery.value == "") return usePage().props.borrowed_supply;
    return usePage().props.borrowed_supply.filter(supply => supply.supply.name.toLowerCase().includes(borrowed_searchQuery.value.toLowerCase()) || supply.borrower.toLowerCase().includes(borrowed_searchQuery.value.toLocaleLowerCase()) || supply.supply.description.toLowerCase().includes(borrowed_searchQuery.value.toLowerCase()));
});

const filterForm = useForm({
    date_from: '',
    date_to: '',
});

const form = useForm({
    name: '',
    quantity: '',
    unit: '',
    status: 0,
    description: '',
    type: ''
});

const quantityForm = useForm({
    item_name: '',
    stock: null,
    quantity: null,
    isAdd: true,
    borrower: '',
    isBorrow: false,
});

const returnForm = useForm({
    quantity: "1",
    id: null,
    maxQuantity: 0,
    name: "",
    supply_id: null
});

function openDeleteModal(id) {
    item_id.value = id;
    isOpenDeleteModal.value = true;
}

function openCreateModal() {
    isOpenCreateModal.value = true;
    isAdd.value = true;
}

function openEditModal(item) {
    item_id.value = item.id;
    isOpenCreateModal.value = true;
    isAdd.value = false;

    form.name = item.name;
    form.unit = item.unit;
    form.status = item.status;
    form.description = item.description;
    form.type = item.type;
}

function openQuantityModal(item) {
    item_id.value = item.id;
    quantityForm.stock = item.quantity;
    quantityForm.item_name = item.name;
    isModifyQuantityModal.value = true;
}

function openReturnModal(item) {
    isOpenReturnModal.value = true;
    returnForm.quantity = item?.quantity ?? 0;
    returnForm.id = item?.id ?? null;
    returnForm.maxQuantity = item?.quantity ?? null;
    returnForm.name = item?.supply?.name ?? null;
    returnForm.supply_id = item?.supply_id ?? null;
}

function closeModal() {
    isOpenCreateModal.value = false;
    form.reset();
}

const submit = () => {
    if (isAdd.value) {
        form.post(route('admin.addItem'), {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    closeModal();
                    showToast(page.props.flash.success, false);
                } else {
                    showToast(page.props.flash.success, true);
                }
            }
        });
    } else {
        form.put(route('admin.editItem', { id: item_id.value }), {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    closeModal();
                    showToast(page.props.flash.success, false);
                } else {
                    showToast(page.props.flash.success, true);
                }
            }
        });
    }
};

const modifyQuantity = () => {
    quantityForm.put(route('admin.editItemQuantity', { id: item_id.value }), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                quantityForm.reset();
                isModifyQuantityModal.value = false;
                showToast(page.props.flash.success, false);
            } else {
                showToast(page.props.flash.success, true);
            }
        }
    });
};


const deleteItem = () => {
    processing.value = true;
    router.delete(route('admin.deleteItem', { id: item_id.value }), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                isOpenDeleteModal.value = false;
                showToast(page.props.flash.success, false);
                processing.value = false;
            } else {
                processing.value = false;
                showToast(page.props.flash.success, true);
            }
        }
    });
};

watch(() => returnForm.quantity, () => {
    if (returnForm.quantity > returnForm.maxQuantity || returnForm.quantity < 0) {
        return_excess_error.value = "Invalid Quantity";
    } else {
        return_excess_error.value = null;
    }
});

watch(() => quantityForm.quantity, () => {
    if (!quantityForm.isAdd) {
        if (quantityForm.quantity > quantityForm.stock) {
            excess_error.value = "Insufficient Stock";
        } else {
            excess_error.value = null;
        }
    } else {
        excess_error.value = null;
    }
});

function changeToAdd() {
    quantityForm.isAdd = true;
    quantityForm.isBorrow = false;
    quantityForm.borrower = "";
}

function toggleBorrow() {
    quantityForm.isBorrow = !quantityForm.isBorrow;
}

const returnBorrowedItem = () => {
    returnForm.put(route('admin.returnItem'), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                isOpenReturnModal.value = false;
                showToast(page.props.flash.success, false);
            } else {
                showToast(page.props.flash.error, true);
            }
        }
    });
};

const filterSupplyActivity = async () => {

    try {
        const response = await axios.get(route("admin.filterSupplyActivity"), {
            params: { date_from: filterForm.date_from, date_to: filterForm.date_to } // Example dates
        });
        if (response.data.count > 0) {
            activity_length.value = response.data.count;
            has_activity.value = true;
        } else {
            has_activity.value = true;
        }

    } catch (error) {
        showToast(error, true);
    }
};

watch(() => ([filterForm.date_from, filterForm.date_to]), ([value1, value2]) => {
    if(value1 != '' && value2 != ''){
        canFilter.value = true;
    }
});

function downloadSupplyActivity() {
    try {
        // Encode as URL parameters
        const data = {
            date_from:filterForm.date_from,
            date_to:filterForm.date_to
        };

        const queryString = new URLSearchParams(data).toString();
        const url = route('admin.DownloadSupplyActivity') + '?' + queryString;

        // Open the route in a new tab
        window.open(url, '_blank');
    } catch (error) {
        console.error('Download Error:', error);
    }
}

</script>

<template>

    <Head title="Supply" />

    <Layout>
        <div class="px-10 py-5 ">
            <div class="text-2xl font-bold">
                Supply Monitoring
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 md:gap-3 py-5">
                <div class="col-span-2">

                    <div class="flex justify-between items-center flex-col md:flex-row">

                        <label @click="openCreateModal()"
                            class="btn bg-green-500 hover:bg-green-700 text-white md:w-16 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                        </label>
                        <div class="flex justify-end flex-col sm:flex-row gap-5 py-5 w-full ">
                            <div class="relative ">
                                <span class="absolute text-gray-500 top-2 left-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>

                                </span>
                                <input type="search"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 min-w-full dark:text-gray-800"
                                    placeholder="Search..." v-model="searchQuery">
                            </div>
                            <select
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800"
                                v-model="typeValue" @change="filterItem()">
                                <option :value="null">All</option>
                                <option value="0">Equipment</option>
                                <option value="1">Consumable</option>
                            </select>
                            <select
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800"
                                v-model="statusValue" @change="filterItem()">
                                <option :value="null">All</option>
                                <option value="0">No Stock</option>
                                <option value="1">In Stock</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex w-full overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="background-color: #004E99; color: white;">#</th>
                                    <th style="background-color: #004E99; color: white;">Item Name</th>
                                    <th style="background-color: #004E99; color: white;">Description</th>
                                    <th style="background-color: #004E99; color: white;">Quantity</th>
                                    <th style="background-color: #004E99; color: white;">Unit</th>
                                    <th style="background-color: #004E99; color: white;">Type</th>
                                    <th style="background-color: #004E99; color: white;">Status</th>
                                    <th style="background-color: #004E99; color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in filteredItems" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.description }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ item.unit }}</td>
                                    <td>{{ item.type == 0 ? 'Equipment' : 'Consumable' }}</td>
                                    <td>
                                        <span class="badge "
                                            :class="item.status == 1 ? 'badge-success' : 'badge-error'">{{ item.status
                                                == 1 ? 'In Stock' : 'No Stock' }}</span>
                                    </td>
                                    <td class="flex gap-3">
                                        <button type="button" class="text-error" @click="openDeleteModal(item.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                        <button @click="openEditModal(item)" type="button" class="text-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            class="rounded-full border shadow bg-transparent hover:bg-gray-100 p-1"
                                            @click="openQuantityModal(item)">
                                            <span class="text-xl text-success">+</span>
                                            <span class="text-xl">/</span>
                                            <span class="text-xl text-error">-</span>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredItems.length <= 0">
                                    <td colspan="8">
                                        <span class="flex items-center justify-center">No Items Added Yet!</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-span-1 mt-5">
                    <div class="w-full flex flex-col gap-3 border rounded-lg shadow-md">
                        <!-- Title -->
                        <div
                            class="text-center flex justify-between gap-3 min-w-full text-white px-5 py-4 rounded-t bg-green-500">
                            <span>Supply Activity</span>
                            <span class="cursor-pointer hover:text-gray-500" @click="dateRangeDrawer = true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </span>
                        </div>


                        <!-- Table Container -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <!-- Table Head (Fixed) -->
                                <thead class="bg-gray-900 text-white sticky top-0 z-10">
                                    <tr>
                                        <th class="p-2 text-center">Item</th>
                                        <th class="p-2 text-center">Quantity</th>
                                        <th class="p-2 text-center">Date Time</th>
                                    </tr>
                                </thead>
                            </table>

                            <!-- Scrollable Table Body -->
                            <div class="max-h-[300px] overflow-y-auto">
                                <table class="min-w-full border-collapse">
                                    <tbody>
                                        <tr v-for="(item, index) in supply_activities" :key="index" class="border-b">
                                            <td class="p-2 text-center">{{ item.item_name }}</td>
                                            <td class="p-2 text-center">
                                                <span
                                                    :class="item.quantity.includes('-') ? 'text-red-500' : 'text-green-500'">
                                                    {{ item.quantity }}
                                                </span>
                                            </td>
                                            <td class="p-2 text-center">{{ new
                                                Date(item.created_at).toLocaleString() }}</td>
                                        </tr>
                                        <tr v-if="supply_activities.length <= 0">
                                            <td colspan="3" class="text-center py-4">No Activity Yet</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 mt-5">

                    <div class="flex justify-between items-center flex-col md:flex-row">

                        <span class="text-2xl font-bold w-full">Borrowed Items</span>
                        <div class="flex justify-end flex-col sm:flex-row gap-5 py-5 w-full ">
                            <div class="relative ">
                                <span class="absolute text-gray-500 top-2 left-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>

                                </span>
                                <input type="search"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 min-w-full dark:text-gray-800"
                                    placeholder="Search..." v-model="borrowed_searchQuery">
                            </div>


                        </div>
                    </div>

                    <div class="flex w-full overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="background-color: #004E99; color: white;">#</th>
                                    <th style="background-color: #004E99; color: white;">Item Name</th>
                                    <th style="background-color: #004E99; color: white;">Quantity</th>
                                    <th style="background-color: #004E99; color: white;">Unit</th>
                                    <th style="background-color: #004E99; color: white;">Date Borrowed</th>
                                    <th style="background-color: #004E99; color: white;">Personnel</th>
                                    <th style="background-color: #004E99; color: white;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in borrowed_filteredItems" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ item.supply.name }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ item.supply.unit }}</td>
                                    <td>{{ new Date(item.created_at).toDateString() }}</td>
                                    <td>{{ item.borrower }}</td>
                                    <td class="flex gap-3">
                                        <button @click="openReturnModal(item)" type="button"
                                            class="btn bg-green-600 text-white hover:bg-green-800 rounded">Return</button>
                                    </td>
                                </tr>
                                <tr v-if="borrowed_filteredItems.length <= 0">
                                    <td colspan="8">
                                        <span class="flex items-center justify-center">No Items Added Yet!</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!--Add modal-->

        <input class="modal-state" id="create-modal" type="checkbox" v-model="isOpenCreateModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5 w-screen">
                <label @click="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">{{ isAdd ? 'Add Item' : 'Edit Item' }}</h2>
                <form @submit.prevent="submit">
                    <div class="flex flex-row gap-3 ">
                        <div class="mb-1 w-full gap-5">
                            <div class="flex flex-col mb-2">
                                <InputLabel for="type" value="Type" class="mb-1" />

                                <select id="type"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.type">
                                    <option value="0">Equipment</option>
                                    <option value="1">Consumable</option>
                                </select>

                                <InputError class="mt-2" :message="errors.type" />
                            </div>

                            <div class="flex flex-col mb-2">
                                <InputLabel for="name" value="Item Name" />

                                <TextInput id="name" type="text" class="mt-1 block min-w-full" v-model="form.name"
                                    required autofocus autocomplete="name" />

                                <InputError class="mt-2" :message="errors.name" />
                            </div>
                            <div class="flex flex-col mb-2">
                                <InputLabel for="unit" value="Unit" />

                                <TextInput id="unit" type="text" class="mt-1 block min-w-full" v-model="form.unit"
                                    required autofocus autocomplete="unit" />

                                <InputError class="mt-2" :message="errors.unit" />
                            </div>
                            <div class="flex flex-col mb-2">
                                <InputLabel for="description" value="Description" />

                                <textarea id="description" v-model="form.description"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Description..."></textarea>

                                <InputError class="mt-2" :message="errors.description" />
                            </div>
                            <div class="flex flex-col mb-2">
                                <InputLabel for="status" value="status" />

                                <select id="status"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.status" disabled>
                                    <option value="1">In Stock</option>
                                    <option value="0">No Stock</option>
                                </select>

                                <InputError class="mt-2" :message="errors.status" />
                            </div>

                            <div class="flex gap-2 py-2">
                                <button type="submit" class="btn-block btn"
                                    :class="isAdd ? 'btn-success' : 'btn-primary'" :disabled="form.processing">
                                    <span>{{ isAdd ? 'Add' : 'Update' }}</span>
                                    <span v-if="form.processing"
                                        class="pl-4 spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-sm"></span>
                                </button>
                                <label @click="closeModal()" class="btn btn-block">Cancel</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!--Add or Minus Quantity modal-->

        <input class="modal-state" id="modify-quantity-modal" type="checkbox" v-model="isModifyQuantityModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="modify-quantity-modal"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Supply Quantity</h2>
                <div class="flex justify-end mb-2">
                    <div class="btn-group btn-group-scrollable">
                        <button class="btn" :class="{ 'btn-primary disabled pointer-events-none': quantityForm.isAdd }"
                            @click="changeToAdd()">Add</button>
                        <button class="btn" :class="{ 'btn-primary disabled pointer-events-none': !quantityForm.isAdd }"
                            @click="quantityForm.isAdd = false">Minus</button>
                    </div>
                </div>
                <input type="text" disabled
                    class="min-w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-600"
                    v-model="quantityForm.item_name">
                <div class="flex justify-between gap-3 mb-2">
                    <span>Total Qty.</span>
                    <span>{{ quantityForm.stock }}</span>
                </div>
                <span class="font-bold">Qty. to {{ quantityForm.isAdd ? 'add' : 'deduct' }}</span>
                <div class="flex justify-between gap-3 items-center">
                    <label for="qty">Qty.</label>
                    <div class="relative">
                        <span class="absolute left-1 text-sm"
                            :class="quantityForm.isAdd ? 'text-success' : 'text-error'" style="top: 0.6rem">
                            <svg v-if="!quantityForm.isAdd" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                        </span>
                        <input v-model="quantityForm.quantity" type="number" min="1" id="qty"
                            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-28 pl-8 dark:text-gray-800">
                        <InputError :message="errors.quantity" />
                        <InputError :message="excess_error" />
                    </div>

                </div>
                <div class="my-2" v-if="!quantityForm.isAdd">
                    <div class="flex justify-between gap-3 items-center">
                        <span>Borrow</span>
                        <input type="checkbox" :checked="quantityForm.isBorrow" v-model="quantityForm.isBorrow"
                            @click="toggleBorrow()">
                    </div>
                    <div v-if="quantityForm.isBorrow" class="mt-2">
                        <InputLabel for="borrower" />
                        <TextInput id="borrower" placeholder="Name" v-model="quantityForm.borrower" />
                        <InputError :message="errors.borrower" />
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button @click="modifyQuantity" type="button" class="btn btn-primary"
                        :disabled="excess_error || quantityForm.processing">Confirm</button>

                    <label class="btn" for="modify-quantity-modal">Cancel</label>
                </div>
            </div>
        </div>


        <!--Delete modal-->

        <input class="modal-state" id="delete-modal" type="checkbox" v-model="isOpenDeleteModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="delete-modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Delete Item</h2>
                <div class="flex flex-col items-center justify-center">
                    <span class="text-error mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-20">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </span>
                    <span class="text-xl">Are you sure you want to delete this item?</span>
                </div>
                <div class="flex justify-end gap-3 my-2">
                    <button @click="deleteItem" type="button" class="btn btn-error"
                        :disabled="processing">Confirm</button>

                    <label class="btn" for="delete-modal">Cancel</label>
                </div>
            </div>
        </div>

        <!--Return Modal-->

        <input class="modal-state" id="return-modal" type="checkbox" v-model="isOpenReturnModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="return-modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Return Item</h2>
                <div class="my-2 flex flex-col gap-2">
                    <TextInput type="text" v-model="returnForm.name" disabled />
                    <InputLabel for="return_quantity" :value="`Quantity (Max = ${returnForm.maxQuantity})`" />
                    <TextInput type="number" v-model="returnForm.quantity" :max="returnForm.maxQuantity" min="1" />
                    <InputError :message="return_excess_error" />
                </div>
                <div class="flex justify-end gap-3 my-2">
                    <button @click="returnBorrowedItem" type="button" class="btn btn-success"
                        :disabled="returnForm.processing || return_excess_error != null">Confirm</button>

                    <label class="btn" for="return-modal">Cancel</label>
                </div>
            </div>
        </div>

        <!--Drawer Right-->

        <input type="checkbox" id="date-span" class="drawer-toggle" v-model="dateRangeDrawer" />

        <label class="overlay" for="date-span"></label>
        <div class="drawer drawer-right">
            <div class="drawer-content pt-10 flex flex-col h-full">
                <label for="date-span" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                    <div>

                        <div class="flex flex-col">
                            <h2 class="text-xl font-medium mb-5">Supply Activity Report</h2>

                            <div class="input-group mb-3">
                                <InputLabel for="date_from" value="From" />

                                <TextInput id="date_from" type="date" class="mt-1 block min-w-full"
                                    v-model="filterForm.date_from" required autofocus autocomplete="date_from" />
                            </div>
                            <div class="input-group mb-3">
                                <InputLabel for="date_to" value="To" />

                                <TextInput id="date_to" type="date" class="mt-1 block min-w-full" v-model="filterForm.date_to"
                                    required autofocus autocomplete="date_to" />
                            </div>


                            <div class=" max-h-[200px] rounded shadow flex justify-between items-center p-3"
                                v-if="has_activity">
                                <span>Found {{ activity_length }} records</span>
                                <span class="text-white bg-primary shadow-md cursor-pointer rounded p-1"
                                    v-if="activity_length > 0" @click="downloadSupplyActivity()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>

                                </span>
                            </div>
                        </div>

                    </div>
                    <div class="h-full flex flex-row justify-end items-end gap-2">
                        <label for="date-span" class="btn btn-ghost">Cancel</label>
                        <button type="buttom" @click="filterSupplyActivity" class="btn bg-blue-700 hover:bg-blue-800 text-white" :disabled="!canFilter">Filter</button>
                    </div>
            </div>
        </div>

    </Layout>
</template>