<template>
    <CustomModal
        :is-active="rolesStore.showCreateModal"
        @clickOutside="rolesStore.closeModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!rolesStore.edit.isEdit" class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.add') }} {{ $t('forms.roles.role') }}</strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.edit') }} {{ $t('forms.roles.role') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="rolesStore.closeModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[550px] grid grid-cols-[1fr,auto] gap-4">
                <div>
                    <SimpleTextInput
                        :isEssential="true"
                        :label="$t('forms.common.name')"
                        errorKeyName="name"
                        v-model="newRole.name"
                        :errors="error"
                    />
                </div>
                <div>
                    <h4 class="text-base font-medium mb-1 text-[#3f4254] text-start">
                        {{ $t('forms.roles.color') }}
                    </h4>
                    <input type="color" v-model="newRole.color" class="h-10 rounded-lg">
                </div>
            </div>
            <section class="permissionsList mt-4 h-[45vh] overflow-y-auto bg-light p-2 rounded-lg text-start">
                <h3 class="font-semibold text-base">{{ $t('forms.roles.rolePermission') }}</h3>
                <article v-for="parent in rolesStore.allPermissions">
                    <div class="transition duration-300 hover:bg-white rounded-lg">
                        <div class="head grid grid-cols-[auto,auto] gap-4 h-[100%]
                                items-center justify-between cursor-pointer text-base select-none p-1"
                             @click="rolesStore.setOpenRoleSubId(parent.id)"
                        >
                            <strong>{{ parent.name }}</strong>
                            <div class="pe-2">
                                <input type="checkbox" class="input-check input-check-sm inline-block me-2"
                                       v-model="parent.areAllChildrenChecked"
                                       @click.stop
                                        @change="selectAllChildren($event, parent)">
                                <SvgComponent
                                    name="fi-rr-angle-left"
                                    size=".6rem"
                                    class="transition-color duration-300 ease-in-out fill-[#5E6278] inline-block"
                                    :style="rolesStore.openRoleSubId === parent.id? {'transform':'rotate(-90deg)'}:{}"
                                />
                            </div>
                        </div>
                        <transition name="fadeHeight">
                            <ul class="ps-6 space-y-2 pt-2 select-none" v-if="rolesStore.openRoleSubId === parent.id">
                                <li v-for="child in parent.children"
                                    class="ps-2 border-b-[1px] pb-1 last:border-none">
                                    <label class="cursor-pointer grid grid-cols-[auto,auto] justify-between w-[100%]">
                                        <span>{{ child.name }}</span>
                                        <input type="checkbox" class="input-check input-check-sm inline-block"
                                               :value="child.id" v-model="newRole.permissionIds"
                                               @change="checkParentPermissionIsChecked(parent)">
                                    </label>
                                </li>
                            </ul>
                        </transition>
                    </div>
                </article>
            </section>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="rolesStore.closeModal" class="btn btn-light btn-active-light">{{$t('forms.common.close')}}</button>
                <button @click="sendDataMethod" class="btn btn-primary btn-active-primary">{{$t('forms.common.save')}}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import {useRolesStore} from "../../../Stores/admin/RolesStore";
import {onMounted, watch, ref} from "vue";

import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";

export default {
    name: "CreateModal",
    components: {SimpleTextInput, CustomModal, SvgComponent},
    setup(){
        const rolesStore = useRolesStore();
        const defaultColor = '#7e8299';
        const newRole = ref({
            name: '',
            color: defaultColor,
            permissionIds: [],
        });
        const error = ref({});

        onMounted(()=>{
            axios.post('/admin/permissions/getAllParentChildren', {})
                .then((res)=>{
                    rolesStore.allPermissions = res.data;
                });
        });

        watch(() => rolesStore.showCreateModal, (value)=>{
            if (value){
                if (rolesStore.edit.isEdit){
                    // set the former parameters
                    newRole.value.name = rolesStore.edit.toBeEdited.name;
                    newRole.value.color = rolesStore.edit.toBeEdited.color;
                    newRole.value.permissionIds = getIds(rolesStore.edit.toBeEdited.permissions);
                    setParentPermissions();
                }else {
                    newRole.value.name = '';
                    newRole.value.color = defaultColor;
                    newRole.value.permissionIds = [];
                }
            }
        });

        function getIds(data){
            let output = [];

            for (const itemKey in data) {
                output.push(data[itemKey].id);
            }
            return output;
        };

        const selectAllChildren = (e, parent)=>{
            let isAllSelected = true;
            for (const key in parent.children) {
                if (newRole.value.permissionIds.includes(parent.children[key].id)){
                    continue;
                }else {
                    isAllSelected = false;
                }
            }
            // above checks if all are selected

            if (isAllSelected){
                // deselect all
                for (const key in parent.children) {
                    newRole.value.permissionIds = newRole.value.permissionIds.filter( item => item === parent.children[key].id );
                }
                // e.target.checked = false;
                parent.areAllChildrenChecked = false;
            }else {
                // select all
                for (const key in parent.children) {
                    newRole.value.permissionIds.push(parent.children[key].id);
                }
                // e.target.checked = true;
                parent.areAllChildrenChecked = true;
            }
        };

        // this loops to see how all parent permissions checkboxes should be when update modal opens.
        function setParentPermissions(){
            for (const key in rolesStore.allPermissions) {
                checkParentPermissionIsChecked(rolesStore.allPermissions[key]);
            }
        }

        //this sets the check marks on parent permission
        const checkParentPermissionIsChecked = (parent)=>{
            let isAllSelected = true;
            for (const key in parent.children) {
                if (newRole.value.permissionIds.includes(parent.children[key].id)){
                    continue;
                }else {
                    isAllSelected = false;
                }
            }

            parent.areAllChildrenChecked = isAllSelected;
        }

        const sendDataMethod = async ()=>{
            rolesStore.showFullScreenLoader = true;

            if (rolesStore.edit.isEdit){
                let roleId = rolesStore.edit.toBeEdited.id;
                await axios.put(`/admin/roles/${roleId}`, newRole.value)
                    .then((res)=>{
                        rolesStore.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({
                                title: 'موفقیت!',
                                message: 'مقام شما با موفقیت ویرایش شد.',
                            });
                            rolesStore.closeModal();
                            newRole.value = {
                                name: '',
                                color: defaultColor, //default
                                permissionIds: [],
                            };
                            error.value = {};
                        }
                    })
                    .catch((_error) => {
                        rolesStore.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: 'خطا!', message: 'اطلاعات فرم ناقص است.'});
                    });
            }else {
                await axios.post('/admin/roles', newRole.value)
                    .then((res)=>{
                        rolesStore.showFullScreenLoader = false;
                        if (res.status === 200){
                            iziToast.success({
                                title: 'موفقیت!',
                                message: 'مقام شما با موفقیت ایجاد شد.',
                            });
                            newRole.value = {
                                name: '',
                                color: defaultColor, //default
                                permissionIds: [],
                            };
                            error.value = {};
                        }
                    })
                    .catch((_error) => {
                        rolesStore.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: 'خطا!', message: 'اطلاعات فرم ناقص است.'});
                    });
            }

            rolesStore.updateTableData();
        };

        return{
            rolesStore,
            newRole,
            error,
            sendDataMethod,
            selectAllChildren,
            checkParentPermissionIsChecked
        }
    }
}
</script>

<style scoped>

</style>
