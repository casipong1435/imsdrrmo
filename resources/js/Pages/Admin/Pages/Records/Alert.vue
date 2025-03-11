<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Layout from '../Layout/Layout.vue'
import { useToast } from "@/composables/useToast";
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const { showToast } = useToast();
const isOpenAlertModal = ref(false);
const isOpenBarangaySelection = ref(false);
const isAllSelected = ref(false);

const searchQuery = ref("");


defineProps({
    errors: Object,
    alerts: Array,
    barangays: Array
});

const filteredBarangays = computed(() => {
    if (searchQuery.value == "") return usePage().props.barangays;
    return usePage().props.barangays.filter(barangay => barangay.barangay_name.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

function selectAll(){

    isAllSelected.value = !isAllSelected.value;

    if(isAllSelected.value){
        form.selectedBarangay = usePage().props.barangays.map(barangay=>barangay.id);
    }else{
        form.selectedBarangay = [];
    }
}

const form = useForm({
    description: '',
    selectedBarangay: []
});


watch(() => form.selectedBarangay, () => {
    if(form.selectedBarangay.length != usePage().props.barangays.length){
        isAllSelected.value = false;
    }
});


function closeModal(){
    isOpenAlertModal.value = false;
}

const sendSMSAlert = () => {
    form.post(route('admin.sendSMS'),{
        onSuccess: (page) => {
           if(page.props.flash.success){
            isOpenAlertModal.value = false;
            form.description = '';
            form.selectedBarangay = [];
            isAllSelected.value = false;
            isOpenBarangaySelection.value = false;
            showToast(page.props.flash.success, false);
           }else{
            showToast(page.props.flash.error, true);
           }
        }
    });
};

</script>

<template>

    <Head title="Calamity Alerts" />

    <Layout>
        <div class="px-10 py-5 h-screen">
            <div class="text-2xl font-bold">
                SMS Calamity Alerts
            </div>

            <div class="grid grid-cols-1">
                <div class="col-span-1">

                    <div class="flex justify-end items-center mb-3">

                        <label for="alertModal"
                            class="btn bg-green-500 hover:bg-green-700 text-white md:w-16 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                        </label>
                        
                    </div>

                    <div class="flex w-full overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="background-color: #004E99; color: white;">#</th>
                                    <th style="background-color: #004E99; color: white;">Message</th>
                                    <th style="background-color: #004E99; color: white;">Created At</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alert, index) in alerts" :key="alert.id">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ alert.description }}</td>
                                    <td>{{ new Date(alert.created_at).toLocaleString() }}</td>
                                </tr>
                                <tr v-if="alerts.length <= 0">
                                    <td colspan="3">
                                        <div class="text-center">No SMS Alert Sent!</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>

        <!--Add modal-->

        <input class="modal-state" id="alertModal" type="checkbox" v-model="isOpenAlertModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5 w-screen">
                <label @click="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Send Alert</h2>
                <form @submit.prevent="sendSMSAlert">
                    <div class="flex flex-row gap-3 ">
                        <div class="mb-1 w-full gap-5">
                            
                            <div class="flex flex-col mb-2">
                                <InputLabel for="description" value="Description" />

                                <textarea id="description"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Description..." v-model="form.description"></textarea>

                                <InputError class="mt-2" :message="errors.description" />

                                <div class="mt-2">
                                    <div class="p-2 flex justify-between items-center bg-gray-200 border-1 hover:bg-gray-300 cursor-pointer" @click="isOpenBarangaySelection = !isOpenBarangaySelection">
                                        <span>Select Barangay</span>
                                        <span>{{ form.selectedBarangay.length }} selected &#x25BC;</span>
                                    </div>
                                    <div class="bg-gray-100 w-full p-2 max-h-[200px] overflow-auto" v-if="isOpenBarangaySelection">
                                        <ul>
                                            <li class="mb-2">
                                                <TextInput type="search" class="min-w-full p-2" v-model="searchQuery" placeholder="Search Barangay..." />
                                            </li>
                                            <li class="flex justify-start gap-2 items-center">
                                                <input type="checkbox" @click="selectAll()" :checked="isAllSelected">
                                                <span>All</span>
                                            </li>
                                            <li class="flex justify-start gap-2 items-center" v-for="(barangay, index) in filteredBarangays" :key=index>
                                                <input type="checkbox" v-model="form.selectedBarangay" :value="barangay.id">
                                                <span>{{ barangay.barangay_name }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <InputError :message="errors.selectedBarangay"/>
                                </div>
                            </div>

                            <div class="flex gap-2 py-2">
                                <button type="submit" class="btn-block btn btn-primary" :disabled="form.processing">
                                    <span>Send</span>
                                    <span v-if="form.processing"
                                        class="pl-4 spinner-dot-intermittent [--spinner-color:var(--gray-8)] spinner-xs"></span>
                                </button>
                                <label @click="closeModal()" class="btn btn-block">Cancel</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </Layout>
</template>