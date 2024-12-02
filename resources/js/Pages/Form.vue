<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const form_list = ref([]);

const check_out = (e) => {
    
}

const delete_form = (e) => {
    form_id.value = e.target.id.replace("delete_","");
    axios.get('/forms_delete/'+form_id.value).then(res=>{
        alert("Form Deleted");
        axios.get("/forms_get").then(res=>{
            form_list.value = res.data;
        });

    })
}


axios.get("/forms_get").then(res=>{
    form_list.value = res.data;
});
</script>

<template>
    
    <Head title="Forms" />
    
    <SideBarMain>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Forms
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <div class="p-1 text-gray-900">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th class="py-3">Form Name</th>
                                        <th class="py-3">Form Description</th>
                                        <th class="py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in form_list" :key="data.id">
                                        <td>{{ data.form_name }}</td>
                                        <td>{{ data.form_description }}</td>
                                        <td>
                                            <button :id="'save_'+data.id" :onclick="check_out" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
                                            <button :id="'delete_'+data.id" :onclick="delete_form" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Delete</button>
                                        </td>
                                        
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SideBarMain>
</template>
