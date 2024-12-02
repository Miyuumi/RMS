<script setup>
import Modal from '@/Components/Modal.vue';
import SideBarMain from '@/Layouts/SideBarMain.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const modal_title = ref("Add New Group");
var group_ctr = 0
const group_list = ref([])
const group_id = ref("")
const group_name = ref("")
const group_description = ref("")
const group_data = ref([])
const group_template = ref([])
const user_data = ref([])
const template_data = ref([])

const check_out = (e) => {
    group_id.value = e.target.id.replace("save_","");
    modal_title.value = "Edit Group";
    axios.get('/groups_find/'+group_id.value).then(res=>{
        group_name.value = res.data.group_name;
        group_description.value = res.data.group_description;

        axios.get('/groups_user/'+group_id.value).then(res=>{
            const data = res.data;
            group_data.value = [];
            for(let x = 0; x < data.length; x++){
                group_data.value.push({"id":x,"user":data[x].user_id})
            }
        })

        axios.get('/groups_template/'+group_id.value).then(res=>{
            const data = res.data;
            group_template.value = [];
            for(let x = 0; x < data.length; x++){
                group_template.value.push({"id":x,"user":data[x].template_id})
            }
        })
        show_modal();
    })
}

const delete_group = (e) => {
    group_id.value = e.target.id.replace("delete_","");
    axios.get('/groups_delete/'+group_id.value).then(res=>{
        alert("Group Deleted");
        axios.get("/groups_get").then(res=>{
            group_list.value = res.data;
        });

    })
}

const new_group = ()=>{
    group_id.value = ""
    group_name.value = "";
    group_description.value = "";
    modal_title.value = "Add New Group";
    show_modal()
}

const add_new_user = ()=>{
    group_data.value.push({"id":group_ctr++,"user":""})
}

const del_new_user = (e) => {
    const del_id =  e.target.id.replace("del_","");
    for(let x = 0; x <  group_data.value.length; x++){
        if(group_data.value[x].id == del_id){
            group_data.value.splice(x,1);
        }
    }
}

const add_new_template = ()=>{
    group_template.value.push({"id":group_ctr++,"user":""})
}

const del_new_template = (e) => {
    const del_id =  e.target.id.replace("delt_","");
    for(let x = 0; x <  group_template.value.length; x++){
        if(group_template.value[x].id == del_id){
            group_template.value.splice(x,1);
        }
    }
}

const open_modal = ref(false);
const selection_modal = ref(false);

const show_modal = ()=>{
    if(!open_modal.value){
        group_data.value = [];
        group_template.value = [];
    }
    open_modal.value = !open_modal.value;
}

const select_modal = ()=>{
    selection_modal.value = !selection_modal.value;
}

const submit = ()=>{
    var data = {
        "id" : group_id.value,
        "group_name" : group_name.value,
        "group_description" : group_description.value,
    };

    for(let x = 0; x < group_data.value.length; x++){
        data["user_"+x] = group_data.value[x].user
    }

    for(let x = 0; x < group_template.value.length; x++){
        data["template_"+x] = group_template.value[x].user
    }
    
    axios.post("/groups_add",data).then(res=>{
        axios.get("/groups_get").then(res=>{
            group_list.value = res.data;
        });
    })
    show_modal();
}



axios.get("/groups_get").then(res=>{
    group_list.value = res.data;
});

axios.get("/users_get").then(res=>{
    user_data.value = res.data;
})

axios.get("/template_get").then(res=>{
    template_data.value = res.data;
})
</script>

<template>
    <Head title="Groups" />

    <SideBarMain>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Group
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <Modal :show="open_modal">
                        <div class="p-6">
                            <div class="flex justify-between">
                                <div>{{ modal_title }}</div>
                                <button :onclick="show_modal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">X</button>
                            </div>
                            <div>
                                <div class="max-w-sm mx-auto">
                                    <input type="hidden" id="id" v-model="group_id"/>
                                    <div class="mb-5">
                                        <label for="group_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Group Name</label>
                                        <input type="text" id="group_name" v-model="group_name" name="group_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Administration" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="group_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Group Description</label>
                                        <textarea id="group_description" v-model="group_description" name="group_description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="In charge of administrative section."></textarea>
                                    </div>
                                   
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Users</label>
                                    <div class="flex items-start mb-5 border-2 rounded-md p-2">
                                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <tbody class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr v-for="item in group_data" :key="item.id">
                                                    <td class="flex justify-between">
                                                        <select id="users" v-model="item.user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option selected value = "">Choose a User</option>
                                                            <option v-for="user in user_data" :key="user.id" :value="user.id">{{ user.name }}</option>
                                                        </select>
                                                        <button type="button" :id="'del_'+item.id" :onclick="del_new_user" class="ml-5 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Remove</button>                
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="flex">
                                        <button type="button" :onclick="add_new_user" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add User</button>
                                    </div>

                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Templates</label>
                                    <div class="flex items-start mb-5 border-2 rounded-md p-2">
                                        
                                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <tbody class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr v-for="item in group_template" :key="item.id">
                                                    <td class="flex justify-between">
                                                        <select id="templates" v-model="item.user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            <option selected value = "">Choose a Template</option>
                                                            <option v-for="user in template_data" :key="user.id" :value="user.id">{{ user.template_name }}</option>
                                                        </select>
                                                        <button type="button" :id="'delt_'+item.id" :onclick="del_new_template" class="ml-5 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Remove</button>                
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                    </div>

                                    <div class="flex">
                                        <button type="button" :onclick="add_new_template" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Template</button>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="button" :onclick="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Modal>
                    <div class="p-1 text-gray-900">
                        <button :onclick="new_group" class="mb-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Add Group</button>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="py-3">Group Name</th>
                                    <th class="py-3">Group Description</th>
                                    <th class="py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in group_list" :key="data.id">
                                    <td>{{ data.group_name }}</td>
                                    <td>{{ data.group_description }}</td>
                                    <td>
                                        <button :id="'save_'+data.id" :onclick="check_out" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">View</button>
                                        <button :id="'delete_'+data.id" :onclick="delete_group" class="m-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Delete</button>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </SideBarMain>
</template>
