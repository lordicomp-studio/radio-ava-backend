<template>
    <section class="roleShow grid grid-cols-[340px,auto] gap-4 p-6">
        <div class="right bg-white rounded-lg p-8">
            <h3 class="text-[#181C32] text-[1.3rem] font-semibold">{{ role.name }}</h3>
            <p class="py-4 text-[#7E8299] text-[13px] font-semibold">
                {{$t('tables.roles.numberOfUsersWhoHaveThisRole')}}: {{ role.users_count }}
            </p>
            <ul class="permissions">
                <li v-for="permission in role.permissions">{{ permission.name }}</li>
            </ul>
        </div>

        <div class="left">
            <section class="tableData userIndex bg-white p-6 rounded">
                <header class="grid grid-cols-[auto,auto] justify-between">
                    <div class="right space-x-2 space-x-reverse">
                        <h3 class="inline-block">{{$t('tables.roles.roleUsers')}}</h3>
                        <span class="inline-block text-[#7E8299] font-normal text-base">({{ role.users_count }})</span>
                    </div>
                    <div class="left">
                        <div class="relative">
                            <div class="absolute right-4 top-3 rotate-[90deg]">
                                <SvgComponent name="fi-rr-search" size="1.2rem" color="#a1a5b7"></SvgComponent>
                            </div>

                            <input v-model="searchText" type="text" :placeholder="$t('tables.common.search')"
                                   class="input-text input-text-solid w-[250px] bg-[#f5f8FA] focus:bg-[#EEF3F7]
                                    pr-[2.8rem!important] text-gray-400 text-[.9rem] font-normal">
                        </div>
                    </div>
                </header>
                <main class="pt-6">
                    <ul>
                        <li class="labels">
                            <span class="">{{$t('fields.ID')}}</span>
                            <span class="">{{$t('fields.user')}}</span>
                            <span class="">{{$t('fields.createdAt')}}</span>
                            <span class="">{{$t('fields.actions')}}</span>
                        </li>
                        <li class="contents" v-for="(user,index) in searchedUsersComputed" :key="index">
                            <span class="text-[#7E8299] font-normal text-[.8rem]">{{ user.id }}</span>

                            <div class="user grid grid-cols-[auto,auto] justify-start gap-2">
                                <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                                    <img :src="user.profile_photo ? `/storage${user.profile_photo.url}` : '/Images/profile default.png'" alt="">
                                </figure>
                                <div class="info grid grid-cols-[auto] items-center">
                                    <a href="" class="text-gray-900 transition-colors duration-300 hover:text-primary">{{user.name}}</a>
                                    <span>{{user.email}}</span>
                                </div>
                            </div>

                            <span class="text-[#7E8299] font-normal text-[.8rem]">{{ user.created_at }}</span>

                            <TableActionsButton>
                                <inertia-link
                                    :href="`/admin/users/${user.id}/edit`"
                                    class="block hover:bg-primary-light text-[#7e8299] text-[.75rem] text-right
                                                hover:text-primary px-3 py-[.2rem] rounded transition duration-200 ease-in-out"
                                >{{$t('tables.common.edit')}}</inertia-link>
                            </TableActionsButton>
                        </li>
                    </ul>
                </main>
            </section>
        </div>
    </section>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import {computed, onBeforeMount, ref} from "vue";
import {useRolesStore} from "../../../Stores/admin/RolesStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";
import TableActionsButton from "@/Components/admin/Widgets/Table/TableActionsButton.vue";

export default {
    name: "RoleShow",
    components: {TableActionsButton, SvgComponent},
    layout: AdminLayout,
    props:{
        role: Object,
    },
    setup(props){
        const users = ref(props.role.users);
        const searchText = ref('');
        const rolesStore = useRolesStore();
        const headerStore = useHeaderStore();

        onBeforeMount(()=>{
            headerStore.title = rolesStore.PageTitleShow;
        });

        const searchedUsersComputed = computed(()=>{
            return users.value.filter((item) => item.name.toLowerCase().includes(searchText.value.toLowerCase()));
        });

        const openActionsMethod = (data) =>{
            if (data.id === rolesStore.openedRowActionPanelId){
                rolesStore.openRowActionPanel(0);
            }else {
                rolesStore.openRowActionPanel(data.id);
            }
        };

        return{
            users,
            searchText,
            searchedUsersComputed,
            openActionsMethod,
            rolesStore,
        }
    },
}
</script>

<style scoped>
    section.roleShow{
        ul.permissions{
            @apply grid gap-2 py-3 grid-cols-[auto,auto];
            li{
                @apply grid grid-cols-[auto,auto] justify-start items-center gap-2
                before:content-[''] before:bg-primary before:w-2 before:h-1 before:rounded
                text-[#7E8299] text-[13px] font-light
            }
        }
    }

    section.tableData ul li{
        @apply grid grid-cols-[5rem,25rem,1fr,6rem]
        py-3 border-b-[1px] border-dashed items-center;
    }

    section.tableData ul li.labels{
        @apply text-sm font-medium;
    }

    section.tableData ul li.labels span{
        @apply text-gray-400
    }

    section.tableData ul li.contents{
        @apply text-gray-500 text-sm;
    }

    section.tableData ul li.contents:last-child{
        @apply border-none pb-4;
    }

    section.tableData .boxed{
        @apply rounded-[.475rem] leading-6 tracking-wide
        px-2 p-0.5
        w-fit
    }

    section.tableData .boxed-wide{
        @apply px-[1rem] p-1.5 font-medium;
    }
</style>
