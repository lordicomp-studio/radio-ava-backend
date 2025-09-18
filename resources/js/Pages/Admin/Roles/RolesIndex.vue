<template>
    <section class="rolesIndex grid grid-cols-3 gap-8 p-4">
        <div
            v-if="rolesStore.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="rolesStore.showFullScreenLoader"/>
        </div>

        <article class="bg-white rounded-lg p-8 grid justify-center items-center">
            <div @click="rolesStore.openModalCreate" class="cursor-pointer group">
                <div class="grid justify-center items-center gap-4">
                    <figure class="h-[150px] w-[150px]">
                        <img src="/Images/FileManagerHeaderBackground.png" alt="">
                    </figure>
                    <h3 class="text-[1.1rem] text-[#7E8299] font-semibold text-center
                        transition duration-200 group-hover:text-primary">
                        {{ $t('tables.common.add') }} {{ $t('pages.Roles') }}
                    </h3>
                </div>
            </div>
        </article>
        <article v-for="role in rolesStore.tableData" class="bg-white rounded-lg p-8">
            <h3 class="text-[#181C32] text-[1.3rem] font-semibold">{{ role.name }}</h3>
            <p class="py-4 text-[#7E8299] text-[13px] font-semibold">
                {{$t('tables.roles.numberOfUsersWhoHaveThisRole')}}: {{ role.users_count }}
            </p>
            <ul class="permissions h-[180px] overflow-hidden justify-start items-start">
                <li v-for="permission in role.permissions">{{ permission.name }}</li>
            </ul>
            <div class="buttons space-x-2 rtl:space-x-reverse">
                <inertia-link :href="`/admin/roles/${role.id}`"
                              class="btn btn-light btn-active-primary"
                >{{ $t('tables.common.show') }}</inertia-link>
                <button class="btn btn-light btn-active-light-primary"
                        @click="rolesStore.openModalEdit(role)">{{ $t('tables.common.edit') }}</button>
                <button class="btn btn-danger btn-active-danger"
                        @click="deleteRowMethod(role)">{{ $t('tables.common.delete') }}</button>
            </div>
        </article>
    </section>
    <CreateModal/>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import CreateModal from "../../../Components/admin/Roles/CreateModal.vue";
import {useRolesStore} from "../../../Stores/admin/RolesStore";
import {onBeforeMount} from "vue";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import {askAffirmation} from "@/scripts/askAffirmation";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "Index",
    components: {Loader, CreateModal},
    layout: AdminLayout,
    props:{
        roles:{
            type: Object,
        }
    },
    setup(props){
        const rolesStore = useRolesStore();
        const headerStore = useHeaderStore();

        onBeforeMount(()=>{
            rolesStore.setTableData(props.roles);
            headerStore.title = rolesStore.PageTitleIndex;
        });

        // the delete button in each data row's action panel
        const deleteRowMethod = (roleData)=>{
            askDeleteAffirmation(roleData);
        };

        function askDeleteAffirmation(roleData){
            askAffirmation(()=>{
                deleteRowFinalize(roleData);
            })
        }

        function deleteRowFinalize(roleData){
            axios.delete(`/admin/roles/${roleData.id}`)
                .then(res => {
                    if (res.data === 200){
                        rolesStore.tableData = rolesStore.tableData.filter( item => item.id !== roleData.id );
                        iziToast.success({
                            title: t('toasts.success'),
                            message: t('toasts.deletedSuccessfully'),
                        });
                    }
                });
        }

        return{
            rolesStore,
            deleteRowMethod,
        }
    },
}
</script>

<style scoped>
    section.rolesIndex{
        ul.permissions{
            @apply grid gap-2 py-3 grid-cols-[auto,auto];
            li{
                @apply grid grid-cols-[auto,auto] justify-start items-center gap-2
                before:content-[''] before:bg-primary before:w-2 before:h-1 before:rounded
                text-[#7E8299] text-[13px] font-light
            }
        }
    }
</style>
