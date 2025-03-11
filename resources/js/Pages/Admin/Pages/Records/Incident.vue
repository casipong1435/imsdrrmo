<script setup>
import Layout from '.././Layout/Layout.vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, reactive } from 'vue';


defineProps({
    errors: Object,
    incidents: Array,
    barangays: Array,
    incident_types: Array,
    total_incidents : Number
});

const dateRangeDrawer = ref(false);
const isBarangayModalOpen = ref(false);
const isDisasterModalOpen = ref(false);
const searchQuery = ref('');
const barangay_incidents = ref([]);
const disasters = ref(null);
const filterDateFrom = ref(usePage().props.date_from);
const filterDateTo = ref(usePage().props.date_to);
const incident_id = ref(null);
const editable = ref(false);

const filteredBarangays = computed(() => {
    if (searchQuery.value == "") return usePage().props.barangays;
    return usePage().props.barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const form = useForm({
    date_from: filterDateFrom,
    date_to: filterDateTo
});

function changeDateRange(){
    router.get(route('admin.incident'), 
    {
        filterDateFrom: filterDateFrom.value,
        filterDateTo: filterDateTo.value,
    },
    {
        preserveScroll: true,
        replace: true,
        preserveState: false
    });
}

const modalInfo = reactive({
    name: '',
});

function openBarangayModal(barangay) {
    modalInfo.name = barangay.barangay_name;
    barangay_incidents.value = barangay.incidents;
    isBarangayModalOpen.value = true;
}

function openDisasterModal(disaster) {
    modalInfo.name = disaster.type;
    disasters.value = disaster;
    isDisasterModalOpen.value = true;
}

function openEditDescription(id) {
    editable.value = true;
    incident_id.value = id;
}

function cancelEditDescription() {
    editable.value = false;
}


const submitEditDescription = () => {

    router.put(route('admin.editIncidentDesctiption'),
        {
            id: incident_id.value,
            description: document.querySelector("#descriptionRef")?.innerText
        },
        {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    editable.value = false;
                } else {
                    showToast(page.props.flash.error, false);
                }
            }
        });
};

function getBarangayName(id) {
    return usePage().props.barangays.find(barangay => barangay.id === id).barangay_name;
}

function getIncidentName(id) {
    return usePage().props.incident_types.find(incident_type => incident_type.id === id).type;
}

function downloadReport(incident) {
    try {
        // Encode as URL parameters
        const data = {
            informant: incident.informant,
            date_time: incident.date_time,
            incident_type: getIncidentName(incident.incident_type_id),
            rationale: incident.description,
            casualties: incident.casualties,
            location: `${incident.purok}, ${getBarangayName(incident.barangay_id)}, Tangub City`
        };

        const queryString = new URLSearchParams(data).toString();
        const url = route('admin.DownloadIncidentReport') + '?' + queryString;

        // Open the route in a new tab
        window.open(url, '_blank');
    } catch (error) {
        console.error('Error opening the certificate page:', error);
    }
}

</script>

<template>

    <Head title="Dashboard" />

    <Layout>
        <div class="px-2 md:px-10 py-5">
            <div class="flex justify-between items-center">
                <span class="text-2xl font-bold">
                    Incidents Reports
                </span>
                <div class="flex gap-2">
                    <label for="date-span" class="btn text-white bg-blue-700 hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>

                    </label>
                    
                </div>
            </div>

            <div class="text-center font-bold text-xl mt-5">
                As of {{ new Date(form.date_from).toDateString() }} - {{ new Date(form.date_to).toDateString() }}
            </div>


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 md:gap-3 py-5">
                <div class="col-span-1 mb-4 md:mb-0">
                    <div class="w-full flex flex-col gap-3 border rounded-lg shadow-md">
                        <!-- Title -->
                        <div class="text-center min-w-full text-white px-5 py-4 rounded-t" style="background: #004E99;">
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
                                <thead class="bg-gray-900 dark:bg-gray-700 text-white sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-2 text-start">Barangay</th>
                                        <th class="px-4 py-2 text-end">Incident</th>

                                    </tr>
                                </thead>
                            </table>

                            <!-- Scrollable Table Body -->
                            <div class="max-h-[300px] overflow-y-auto">
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
                    <div class="w-full flex flex-col gap-3 border rounded-lg shadow-md">
                        <!-- Title -->
                        <div class="text-center min-w-full text-white px-5 py-4 rounded-t" style="background: #004E99;">
                            Disasters
                        </div>

                        <!-- Table Container -->
                        <div class="overflow-x-auto p-2">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 md:gap-5 ">
                                <div class="col-span-1 shadow p-5 rounded hover:bg-gray-100 hover:dark:bg-gray-500 cursor-pointer"
                                    v-for="(incident_type, index) in incident_types" :key="index"
                                    @click="openDisasterModal(incident_type)">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-xl">{{ incident_type.type }}</span>
                                        <span class="font-bold text-xl">{{ incident_type.incident_count }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="font-bold me-2">Total Incident:</span>
                                <span>{{ total_incidents }}</span>
                            </div>
                        </div>
                    </div>
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
                        <h2 class="text-xl font-medium mb-5">Filter Date</h2>
                        <div class="input-group mb-3">
                            <InputLabel for="date_from" value="From" />

                            <TextInput id="date_from" type="date" class="mt-1 block min-w-full" v-model="filterDateFrom"
                                required autofocus autocomplete="date_from" />
                        </div>
                        <div class="input-group">
                            <InputLabel for="date_to" value="To" />

                            <TextInput id="date_to" type="date" class="mt-1 block min-w-full" v-model="filterDateTo"
                                required autofocus autocomplete="date_to" />
                        </div>
                    </div>

                </div>
                <div class="h-full flex flex-row justify-end items-end gap-2">
                    <label for="date-span" class="btn btn-ghost">Cancel</label>
                    <button @click="changeDateRange()" class="btn bg-blue-700 hover:bg-blue-800 text-white">Filter</button>
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


        <!--Modal for Disaster on click-->

        <input class="modal-state" id="disaster_modal" type="checkbox" v-model="isDisasterModalOpen" />
        <div class="modal">
            <label class="modal-overlay" for="disaster_modal"></label>
            <div class="modal-content flex flex-col gap-5 w-screen">
                <label for="disaster_modal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">{{ modalInfo.name }}</h2>
                <div class="accordion-group">
                    <div class="text-xl text-center">Incident Record</div>
                    <div class="my-1 flex justify-between">
                        <span>Barangay</span>
                        <span>Date of Occurence</span>
                    </div>
                    <div class="accordion" v-for="incident in disasters?.incidents" :key="incident.id">
                        <input type="checkbox" :id="'disaster'+incident.id" class="accordion-toggle" />
                        <label :for="'disaster'+incident.id"
                            class="flex justify-between items-center border-b py-2 cursor-pointer">
                            <span class="text-xl font-bold">{{ incident.barangay.barangay_name }}</span>
                            <span>{{ new Date(incident.date_time).toLocaleString() }}</span>
                        </label>
                        <div class="accordion-content">
                            <div class="min-h-0">
                                <div class="flex justify-between items-start gap-3 p-1" v-if="incident.incident_type_id == 8">
                                    <span class="font-bold">Specific Incident:</span>
                                    <span class="text-end">{{ incident.other_incident }}</span>
                                </div>
                                <div class="flex justify-between items-start gap-3 p-1 mb-1">
                                    <span>Options:</span>
                                    <div class="flex gap-1">
                                        <span class="text-success cursor-pointer" @click="downloadReport(incident)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>

                                        </span>
                                        <span v-if="!editable" class="text-primary cursor-pointer"
                                            @click="openEditDescription(incident.id)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </span>
                                        <span v-if="editable" class="cursor-pointer text-primary"
                                            @click="submitEditDescription">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>


                                        </span>
                                        <span v-if="editable" class="cursor-pointer text-warning"
                                            @click="cancelEditDescription()">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>

                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start gap-3 p-1">
                                    <span class="font-bold">Description:</span>
                                    <span class="text-justify" :contenteditable="editable" id="descriptionRef">{{
                                        incident.description
                                        }}</span>
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
                    <div class="mt-2" v-if="disasters?.incidents?.length <= 0">
                        <div class="text-center">(No Incidents Recorded!)</div>
                    </div>
                </div>
                <div class="flex gap-3">

                    <label for="disaster_modal" class="btn btn-block">Close</label>
                </div>
            </div>
        </div>
    </Layout>
</template>