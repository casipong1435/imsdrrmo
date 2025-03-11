<script setup>
import Layout from './Layout/Layout.vue'
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { useToast } from "@/composables/useToast";
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed, reactive, onMounted } from 'vue';

const { showToast } = useToast();

defineProps({
    errors: Object,
    incidents: Array,
    barangays: Array,
    incident_types: Array,
    certificate_requests: Array,
    total_incidents: Number
});

const dateRangeDrawer = ref(false);
const isBarangayModalOpen = ref(false);
const isDisasterModalOpen = ref(false);
const isOpenRequestModal = ref(false);
const searchQuery = ref('');
const isOpenRequest = ref(false);
const isEdit = ref(false);
const incident_id = ref(null);
const barangay_incidents = ref([]);
const disasters = ref(null);
const isOpenRequestConfirmation = ref(false);
const filterDateFrom = ref(usePage().props.date_from);
const filterDateTo = ref(usePage().props.date_to);
const full_name = ref('');
const isDate = ref(false);


const filteredBarangays = computed(() => {
    if (searchQuery.value == "") return usePage().props.barangays;
    return usePage().props.barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const form = useForm({
    date_from: filterDateFrom,
    date_to: filterDateTo
});

const requestForm = useForm({
    recipient: '',
    address: '',
    reason: '',
    incident: '',
    incident_id:''
});

function changeDateRange() {
    router.get(route('user.dashboard'),
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

function openDateDrawer(){
    isDate.value = true;
    dateRangeDrawer.value = true
}

function openAddRequestModal() {
    isDate.value = false;
    isOpenRequestModal.value = true;
    isOpenRequest.value = true;
}

function canRequest(affected_persons) {

    return affected_persons.filter(person => person.name.toLowerCase().includes(full_name.value.toLocaleLowerCase())).length > 0;


}

function openRequestConfirmationModal(incident) {

    isOpenRequestConfirmation.value = true;
    requestForm.incident_id = incident.id;
    requestForm.recipient = full_name;
    requestForm.reason = incident.description;
    requestForm.address = incident.purok + ", " + incident.barangay.barangay_name;
    requestForm.incident = incident.incident_type_id == 8 ? incident.other_incident : usePage().props.incident_types.find(incident_type => incident_type.id == incident.incident_type_id).type;

}

const submitRequest = () => {
    requestForm.post(route('user.requestCertificate'), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                isOpenRequestConfirmation.value = false;
                showToast(page.props.flash.success, false);
            } else {
                showToast(page.props.flash.error, true);
            }
        }
    });
};

onMounted(() => {
    full_name.value = usePage().props.auth.user.first_name + " " + usePage().props.auth.user.last_name;
});

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
                    <label @click="openDateDrawer()" class="btn text-white bg-blue-700 hover:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>

                    </label>
                    <label @click="openAddRequestModal()" class="btn text-white bg-green-600 hover:bg-green-700">
                        Make Request
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
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10 min-w-full"
                                placeholder="Search Barangay..." v-model="searchQuery">
                        </div>

                        <!-- Table Container -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border-collapse">
                                <!-- Table Head (Fixed) -->
                                <thead class="bg-gray-900 text-white sticky top-0 z-10">
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
                                            class="border-b hover:bg-gray-100 cursor-pointer">
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
                                <div class="col-span-1 shadow p-5 rounded hover:bg-gray-100 cursor-pointer"
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
        <div v-if="isDate" class="drawer drawer-right">
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
                    <button @click="changeDateRange()"
                        class="btn bg-blue-700 hover:bg-blue-800 text-white">Filter</button>
                </div>
            </div>
        </div>

        <!--Drawer Request-->

        <input type="checkbox" id="request-drawer" class="drawer-toggle" v-model="isOpenRequestModal" />
        <label class="overlay" for="request-drawer"></label>
        <div v-if="!isDate" class="drawer drawer-right">
            <div class="drawer-content pt-10 flex flex-col h-full">
                <label for="request-drawer" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                    @click="isOpenRequest = false">✕</label>
                <div>

                    <div class="flex flex-col">
                        <h2 class="text-xl font-medium mb-5">Incidents</h2>
                        <span class="text-sm mb-3 text-center">As of {{ new Date(form.date_from).toDateString() }} - {{
                            new Date(form.date_to).toDateString() }}</span>

                        <div class="input-group mb-3 flex justify-between items-center p-2 shadow rounded cursor-pointer hover:bg-gray-50"
                            v-for="(incident_type, index) in incident_types" :key="index"
                            @click="openDisasterModal(incident_type)">
                            <div class="font-bold">{{ incident_type.type }}</div>
                            <span>{{ incident_type.incident_count }}</span>
                        </div>

                    </div>

                </div>
                <div class="h-full flex flex-row justify-end items-end gap-2">
                    <label for="request-drawer" class="btn btn-ghost" @click="isOpenRequest = false">Close</label>
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
        <div class="modal" style="z-index: 99999;">
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
                        <input type="checkbox" :id="'disaster' + incident.id" class="accordion-toggle" />
                        <label :for="'disaster' + incident.id"
                            class="flex justify-between items-center border-b py-2 cursor-pointer">
                            <span class="text-xl font-bold">{{ incident.barangay.barangay_name }}</span>
                            <span>{{ new Date(incident.date_time).toLocaleString() }}</span>
                        </label>
                        <div class="accordion-content">
                            <div class="min-h-0">

                                <div class="flex justify-between items-start gap-3 p-1"
                                    v-if="incident.incident_type_id == 8">
                                    <span class="font-bold">Specific Incident:</span>
                                    <span class="text-end">{{ incident.other_incident }}</span>
                                </div>

                                <div class="flex justify-between items-start gap-3 p-1" v-if="isOpenRequest">
                                    <span class="font-bold"></span>
                                    <button
                                        class="float-end border border-green-600 p-1 rounded text-green-600 hover:bg-green-600 hover:text-white transition"
                                        @click="openRequestConfirmationModal(incident)" style="font-size: 10px;"
                                        :class="{ 'opacity-50 pointer-events-none': !canRequest(incident.affected_persons) }">Request</button>

                                </div>
                                <div class="flex justify-between items-start gap-3 p-1">
                                    <span class="font-bold">Description:</span>
                                    <span class="text-justify">{{ incident.description }}</span>

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

        <!--Modal for Request Confirmation on click-->

        <input class="modal-state" id="confirm_request_modal" type="checkbox" v-model="isOpenRequestConfirmation" />
        <div class="modal" style="z-index: 99999;">
            <label class="modal-overlay" for="confirm_request_modal"></label>
            <div class="modal-content flex flex-col gap-5 w-80">
                <label for="confirm_request_modal"
                    class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl font-bold ">Confirm Request</h2>
                <div class="input-group">
                    <InputLabel value="Recipient" for="recipient" />
                    <TextInput id="recipient" v-model="requestForm.recipient" class="min-w-full" />
                </div>
                <div class="input-group">
                    <InputLabel value="Recipient" for="incident" />
                    <TextInput id="incident" v-model="requestForm.incident" class="min-w-full" />
                </div>
                <div class="input-group">
                    <InputLabel value="Recipient" for="address" />
                    <TextInput id="address" v-model="requestForm.address" class="min-w-full" />
                </div>
                <div class="input-group">
                    <InputLabel value="Recipient" for="reason" />
                    <textarea id="reason" v-model="requestForm.reason"
                        class="min-w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        rows="5"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button class="btn btn-success" type="button" @click="submitRequest" :disabled="requestForm.processing">Confirm</button>
                    <label for="confirm_request_modal" class="btn ">Close</label>
                </div>
            </div>
        </div>
    </Layout>
</template>