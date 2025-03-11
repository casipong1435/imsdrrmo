<script setup>
import Layout from './Layout/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import Chart from "chart.js/auto";
import { ref, onMounted, watch, computed } from "vue";

defineProps({
    zero_stock: Number,
    incident_types: Array,
    incident_type_id: Number,
    barangays: Array,
    year_selected:String,
    selected_incident_type: Object,
    monthly_count: Array
});

const isBarangayModalOpen = ref(false);
const modalInfo = ref('');
const barangay_incidents = ref([]);
const searchQuery = ref('');
const year_selected = ref(usePage().props.year_selected);
const years = ref([]);
const incident_type_id = ref(usePage().props.incident_type_id);
let chartLineInstance = null;
let chartPieInstance = null;
const months = ref(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
const monthlyCounts = ref(new Array(12).fill(0));

const generateYears = () => {
    const currentYear = new Date().getFullYear();
    const availableYears = [];
    for (let year = 2025; year <= currentYear; year++) {
        availableYears.push(year);
    }
    years.value = availableYears;
};

const filteredBarangays = computed(() => {
    if (searchQuery.value == "") return usePage().props.barangays;
    return usePage().props.barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

function changeData(){
    router.get(route('admin.dashboard'),{year_selected:year_selected.value, incident_type_id:incident_type_id.value},{
        preserveScroll: true
    });
}

function openBarangayModal(barangay) {
    modalInfo.name = barangay.barangay_name;
    barangay_incidents.value = barangay.incidents;
    isBarangayModalOpen.value = true;
}

const getIncidentName = () => {
    return usePage().props.incident_types.find(incident_type => incident_type.id == incident_type_id.value);
};


const renderLineChart = () => {
    const ctx = document.getElementById('yearly-incident').getContext('2d');

    if (chartLineInstance) {
        chartLineInstance.destroy();
    }

    chartLineInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months.value,
            datasets: [
                {
                    label: `${getIncidentName().type} Incident (${year_selected.value})`,
                    data: monthlyCounts.value,
                    borderColor: '#2196F3',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#2196F3',
                    pointBorderColor: '#388E3C',
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Months',
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'occurence',
                    },
                    beginAtZero: true,
                },
            },
        },
    });
};


function getIncidentColors() {
    const colors = ['#4CAF50', '#FF9800', '#2196F3', '#FF5722', '#9C27B0', '#a85632', '#32a896', '#512ad1'];
    return colors.slice(0, usePage().props.incident_types.length); // Ensure color array is equal to area count
}

function getIncidentNames() {
    return Object.values(usePage().props.incident_types).map((incident) => incident.type);
}

function getIncidentCounts() {
    return Object.values(usePage().props.incident_types).map((incident) => incident.incidents_count);
}

// Pie Chart Rendering Logic
const renderPieChart = () => {
    const ctx = document.getElementById('incident-pie-chart').getContext('2d');

    if (chartPieInstance) {
        chartPieInstance.destroy();
    }

    chartPieInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: getIncidentNames(),
            datasets: [
                {
                    label: `Incident Occurence (${year_selected.value})`,
                    data: getIncidentCounts(),
                    backgroundColor: getIncidentColors(),
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        },
    });
};

onMounted(() => {
    monthlyCounts.value = usePage().props.monthly_count;
    generateYears();
    renderLineChart();
    renderPieChart();
});

</script>

<template>

    <Head title="Dashboard" />

    <Layout>
        <div class="px-2 md:px-10 py-5">
            <div class="flex items-center justify-between mb-6 gap-3">
                <h1 class="text-3xl font-semibold  text-gray-800">Dashboard</h1>
                <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:text-gray-800" v-model="year_selected" @change="changeData()">
                    <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                </select>
            </div>
            <div v-if="zero_stock > 0"
                class="w-full px-4 py-2 shadow bg-yellow-200 rounded flex justify-between gap-4 items-center mb-4">
                <span class="font-bold">There are {{ zero_stock }} item/s in the inventory that is out of stock!</span>
                <a :href="route('admin.supply')"
                    class="btn bg-yellow-500 hover:bg-yellow-700 text-white shadow rounded-none">View Now</a>
            </div>

            <!--Analytics-->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 py-5">
                <div class="col-span-1 mb-4">
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-xl font-bold">Incident Analytics</span>
                        <select v-model="incident_type_id" @change="changeData()">
                            <option v-for="incident_type in incident_types" :key="incident_type.id"
                                :value="incident_type.id">{{ incident_type.type }}</option>
                        </select>
                    </div>
                    <!-- Chart Section -->
                    <div class="bg-white shadow-xl rounded-lg p-6 border border-gray-200 mt-6">
                        <canvas id="yearly-incident"></canvas>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="w-full flex flex-col gap-3 border rounded-lg shadow-md">
                        <!-- Title -->
                        <div class="text-center min-w-full px-5 py-4 rounded-t shadow">
                            Barangay Disasters
                        </div>
                        <div class="relative px-2">
                            <span class="absolute text-gray-500 top-2 left-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>

                            </span>
                            <input type="search"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 min-w-full dark:text-gray-800"
                                placeholder="Search Barangay..." v-model="searchQuery">
                        </div>

                        <!-- Table Container -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <!-- Table Head (Fixed) -->
                                <thead class="bg-blue-700 dark:bg-gray-700 text-white sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-2 text-start">Barangay</th>
                                        <th class="px-4 py-2 text-end">Incident</th>

                                    </tr>
                                </thead>
                            </table>

                            <!-- Scrollable Table Body -->
                            <div class="max-h-[230px] overflow-y-auto">
                                <table class="min-w-full border-collapse">
                                    <tbody>
                                        <tr v-for="(barangay, index) in filteredBarangays" :key="index"
                                            @click="openBarangayModal(barangay)"
                                            class="border-b hover:bg-gray-100 hover:dark:bg-gray-500 cursor-pointer">
                                            <td class="px-4 py-2">{{ barangay.barangay_name }}</td>
                                            <td class="px-4 py-2 text-center">
                                                <span>
                                                    {{ barangay.incidents.length }}
                                                </span>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <div class="text-xl font-bold mb-10">
                        <div class="text-center">Pie Chart Incident</div>
                    </div>
                    <div class="bg-white shadow-xl rounded-lg p-6 border border-gray-200 mt-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4"></h2>
                        <canvas id="incident-pie-chart" style="width: 100%; max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal for Barangay on click-->

        <input class="modal-state" id="barangay_modal" type="checkbox" v-model="isBarangayModalOpen" />
        <div class="modal !h-full !items-start overflow-y-auto p-4">
            <label class="modal-overlay" for="barangay_modal"></label>
            <div class="modal-content flex flex-col gap-5 w-screen">
                <label for="barangay_modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl font-bold">{{ modalInfo.name }}</h2>
                <div class="accordion-group">
                    <div class="text-xl text-center">Incident Record</div>
                    <div class="accordion" v-for="incident in barangay_incidents" :key="incident.id">
                        <input type="checkbox" :id="incident.id" class="accordion-toggle" />
                        <label :for="incident.id"
                            class="flex justify-between items-center border-b py-2 cursor-pointer">
                            <span class="text-xl font-bold">{{ incident.incident_type_id != 8 ?
                                incident.incident_type.type : incident.other_incident }}</span>
                            <span>{{ new Date(incident.date_time).toLocaleString() }}</span>
                        </label>
                        <div class="accordion-content">
                            <div class="min-h-0">
                                <div class="flex justify-between items-start gap-3 p-1">
                                    <span class="font-bold">Description:</span>
                                    <span class="text-end">{{ incident.description }}</span>
                                </div>
                                <div class="flex justify-between items-start gap-3 p-1 mb-1">
                                    <span class="font-bold">Casualties:</span>
                                    <span class="text-end">{{ incident.casualties }}</span>
                                </div>
                                <div class="flex justify-between items-start gap-3 p-1 mb-1">
                                    <span class="font-bold">Informant:</span>
                                    <span class="text-end">{{ incident.informant }}</span>
                                </div>
                                <div class="p-1 font-bold my-2 text-warning text-center">Affected Person/s</div>
                                <div class=" p-1" v-for="person in incident.affected_persons" :key="person.id">
                                    <div class="flex justify-between items-start gap-3">
                                        <span class="font-bold">Name:</span>
                                        <span class="text-end">{{ person.name }}</span>
                                    </div>
                                    <div class="flex justify-between items-start gap-3">
                                        <span class="font-bold">Purok:</span>
                                        <span class="text-end">{{ person.purok }}</span>
                                    </div>
                                </div>
                                <div v-if="incident.affected_persons.length <= 0" class="text-center">
                                    (No Affected Person/s)
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-2" v-if="barangay_incidents.length <= 0">
                        <div class="text-center">(No Incidents Recorded!)</div>
                    </div>
                </div>
                <div class="flex gap-3">

                    <label for="barangay_modal" class="btn btn-block">Close</label>
                </div>
            </div>
        </div>

    </Layout>
</template>