<script setup>
import Layout from './Layout/Layout.vue'
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import { nextTick, ref, onMounted , computed} from 'vue';
import axios from 'axios';

const contentRef = ref(null);
const selectedRequest = ref(null);
const isOpenDrawer = ref(false);
const searchQuery = ref("");
defineProps({
    certificate_requests: Array
});

const filteredRequest = computed(() => {
     if(searchQuery.value == '') return usePage().props.certificate_requests;
    return usePage().props.certificate_requests.filter(request => request.recipient.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const countUngrantedRequest = computed(() => {
    return usePage().props.certificate_requests.filter(request => request.date_given === null).length;
});

const options = { day: 'numeric', month: 'long', year: 'numeric' };
const date = new Date();
const formattedDate = new Intl.DateTimeFormat('en-US', options).format(date);

// Define reactive data for placeholders
const placeholders = ref({
    id: null,
    recipient: null,
    address: null,
    reason: null,
    incident: null,
    date: null,
    date_given: new Intl.DateTimeFormat('en-US', options).format(date)
});

// Add suffix to the day
const day = date.getDate();
const suffix = (day) => {
    if (day > 3 && day < 21) return "th";
    switch (day % 10) {
        case 1: return "st";
        case 2: return "nd";
        case 3: return "rd";
        default: return "th";
    }
};

const request_data = ref(null);

function getRequestData(request){
    request_data.value = request;
}

function fillCertificate(){
    placeholders.value.id = request_data.value.id;
    placeholders.value.recipient = request_data.value.recipient;
    placeholders.value.address = request_data.value.address;
    placeholders.value.reason = request_data.value.reason;
    placeholders.value.incident = request_data.value.incident;
    placeholders.value.date = request_data.value.date;
    cancelSelect()
    replacePlaceholders();
}

function cancelSelect(){
    selectedRequest.value = null;
    isOpenDrawer.value = false;
}

// Function to update placeholders dynamically

const replacePlaceholders = () => {
    nextTick(() => {
        if (contentRef.value) {
            contentRef.value.innerHTML = `
        <div class="font-bold uppercase mb-8">to whom it may concern</div>
            <div class="indent-20 text-justify mb-5"  id="firstParagraph">
                <b>THIS IS TO CERTIFY THAT </b>
                <u>${placeholders.value.recipient ?? 'Name of the Recepient'}</u>
                of legal age, a bonafide resident of 
                ${placeholders.value.address ?? 'Purok, Barangay, Tangub City'}, Misamis Occidental appeared in this office to ask certification of damage in support for asking financial assitance because 
                ${placeholders.value.reason ?? 'reason for asking'} due to ${placeholders.value.incident ?? 'incident that happend'} incident and it happened last ${new Date(placeholders.value.date).toDateString() ?? 'date happend'}.
            </div>
            <div class="indent-20 text-justify mb-5" id="secondParagraph">
                This CERTIFICATION is being isued upon the request of the herein interested party for whatever any legal purpose it may serve.
            </div>
            <div class="indent-20 text-justify mb-20" id="thirdParagraph">
                Given this ${day}${suffix(day)} day of ${formattedDate.split(' ')[0]}, ${date.getFullYear()} at Tangub City Disaster Operation Center Office, Migcanaway, Tangub City, Misamis Occidental, Philippines.
            </div>
            <div class="mb-10">Very truly yours,</div>
            <b>ENGR. GERONIMO M. MANTUHAC</b>
            <br>
            OIC-CDRRMO Officer
      `;
        }
    });
};

// Initialize placeholders on mount
onMounted(() => {
    replacePlaceholders();
});


// Access refs after nextTick and ensure the DOM is updated
const submitData = () => {
    nextTick(() => {
        const paragraphs = {
            firstParagraph: document.querySelector("#firstParagraph")?.innerText || "",
            secondParagraph: document.querySelector("#secondParagraph")?.innerText || "",
            thirdParagraph: document.querySelector("#thirdParagraph")?.innerText || "",
            id: placeholders.value.id
        };

        if (paragraphs.firstParagraph && paragraphs.secondParagraph && paragraphs.thirdParagraph) {
            try {
                // Encode as URL parameters
                const queryString = new URLSearchParams(paragraphs).toString();
                const url = route('admin.DownloadCertificate') + '?' + queryString;

                // Open the route in a new tab
                window.open(url, '_blank');
            } catch (error) {
                console.error('Error opening the certificate page:', error);
            }
        } else {
            console.error('One of the paragraphs is missing.');
        }
    });
};


</script>

<template>

    <Head title="Certificate" />

    <Layout>
        <div class="px-2 md:px-10 py-5">
            <div class="text-2xl font-bold mb-3">
                Certificate Requests
            </div>
            <div class="my-4 flex justify-end gap-3 items-center">
                <button class="btn rounded btn-success text-md" @click="submitData">
                    <span class="me-2">Export</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </span>
                </button>
                <div class="relative p-1 ">
                    <span class="badge badge-error absolute rounded-full top-0 right-0 z-10">{{ countUngrantedRequest }}</span>
                    <label for="drawer-requests" class="btn cursor-pointer rounded-full hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>

                </label>
                </div>
            </div>
            <div class="text-start dark:text-gray-100 opacity-40">This content is editable</div>
            <div class="border shadow p-14 relative dark:shadow-gray-400 dark:shadow-md">
                
                <div class="text-center flex flex-col mb-6">
                    <span class="text-sm">Republic of the Philippines</span>
                    <span class="uppercase font-bold">city of tangub</span>
                    <span class="uppercase">Tangub city disaster risk reduction and management office</span>
                </div>
                <div class="text-2xl text-center my-8">Certification of Damage</div>
                <div ref="contentRef" contenteditable="true" class="p-1">
                </div>
            </div>
        </div>


        <input type="checkbox" id="drawer-requests" class="drawer-toggle" v-model="isOpenDrawer" />

        <label class="overlay"></label>
        <div class="drawer drawer-right">
            <div class="drawer-content pt-10 flex flex-col h-full">
                <label for="drawer-requests" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <div>
                    <h2 class="text-xl font-medium mb-4">Requests</h2>
                    <div class="px-2 py-4 overflow-y-auto" style="height: 480px;">
                        <TextInput type="search" v-model="searchQuery" class="mb-4 min-w-full dark:text-gray-600" placeholder="Search Request..."/>
                        <label :for="`radio${request.id}`" class="w-full p-2 flex flex-col border rounded cursor-pointer hover:bg-gray-50 mb-3 hover:dark:bg-gray-800" :class="{'bg-gray-50 dark:bg-gray-800':selectedRequest == request.id}" v-for="(request, index) in filteredRequest" :key="index" @click="getRequestData(request)">
                            <input type="radio" class="hidden" :id="`radio${request.id}`" :value="request.id" v-model="selectedRequest">
                            <div class="text-center">{{ request.recipient }}</div>
                            <div class="flex justify-between mt-2 gap-2">
                                <span class="font-bold">Incident:</span>
                                <span class="">{{ request.incident }}</span>
                            </div>
                            <div class="flex justify-between mt-2 gap-2">
                                <span class="font-bold">Happened on:</span>
                                <span class="">{{ new Date(request.date).toLocaleDateString() }}</span>
                            </div>
                            <div class="flex justify-between mt-2 gap-2">
                                <span class="font-bold">Date Requested:</span>
                                <span class="">{{ new Date().toLocaleDateString() }}</span>
                            </div>
                            <div class="flex justify-between mt-2 gap-2">
                                <span class="font-bold">Date Given:</span>
                                <span :class="request.date_given ? 'text-green-600' : 'text-red-600' ">{{ request.date_given ? new Date(request.date_given).toLocaleDateString() : 'Not Yet' }}</span>
                            </div>
                        </label>
                        <div v-if="certificate_requests.length <= 0" class="text-center dark:text-gray-100 font-medium">No Requests Yet!</div>
                    </div>
                </div>
                <div class="h-full flex flex-row justify-end items-end gap-2">
                    <label  class="btn btn-ghost" @click="cancelSelect()">Close</label>
                    <button class="btn btn-primary" :disabled="selectedRequest == null" @click="fillCertificate">Create</button>
                </div>
            </div>
        </div>
    </Layout>
</template>