<template>
    <CustomModal
        :is-active="permissionsStore.showCreateModal"
        @clickOutside="permissionsStore.closeModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!permissionsStore.edit.isEdit" class="text-[1.2rem] leading-[35px]">
                    {{ $t('tables.common.add') }} {{ $t('forms.permissions.permission') }}
                </strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">
                    {{ $t('tables.common.edit') }} {{ $t('forms.permissions.permission') }}
                </strong>

                <div class="no-bg-svg-btn-blue" @click="permissionsStore.closeModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <SimpleTextInput
                    :isEssential="true"
                    :label="$t('forms.common.name')"
                    errorKeyName="name"
                    v-model="newPermission.name"
                    :errors="error"
                />
            </div>
            <div class="w-[500px] mt-4 text-start">
                <h4 class="mb-2 text-[#3f4254]">
                    {{$t('forms.permissions.parentPermission')}}
                    <span class="text-danger">*</span>
                </h4>
                <SelectBasic
                    v-model="newPermission.parent_name"
                    ref="selectComponent"
                    :options-array="permissionsStore.allDataNames"
                />
                <p v-if="error['parent_name']" class="text-danger">{{ error['parent_name'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="permissionsStore.closeModal"
                        class="btn btn-light btn-active-light">{{ $t('forms.common.close') }}</button>
                <button @click="sendDataMethod"
                        class="btn btn-primary btn-active-primary">{{ $t('forms.common.save') }}</button>

            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import SelectBasic from "../Widgets/Input/Selects/SelectBasic.vue";
import {usePermissionsStore} from "../../../Stores/admin/PermissionsStore";
import {onMounted, ref, watch} from "vue";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "CreateModal",
    components: {SimpleTextInput, SelectBasic, CustomModal, SvgComponent},
    setup(){
        const permissionsStore = usePermissionsStore();
        const newPermission = ref({});
        const error = ref({});

        const selectComponent = ref();
        onMounted(()=>{
            axios.post('/admin/permissions/getParents', {})
                .then((res)=>{
                    permissionsStore.allDataNames = extractNamesMethod(res.data);
                });
        })

        // this watch is triggered whenever the modal opens up
        watch(() => permissionsStore.showCreateModal, (value)=>{
            if (value){
                if (permissionsStore.edit.isEdit){
                    newPermission.value.name = permissionsStore.edit.toBeEdited.name;
                    newPermission.value.parent_name = getPermissionParentName(permissionsStore.edit.toBeEdited) ?
                        getPermissionParentName(permissionsStore.edit.toBeEdited) : 'is parent';
                }else {
                    newPermission.value.name = '';
                    newPermission.value.parent_name = '';
                }
            }
        });


        // TODO BUG: if parent permission is not in the same page, it can't find it.
        function getPermissionParentName(permission){
            const parent_id = permission.parent_id;

            let permissions = permissionsStore.tableData.data;
            for (const key in permissions) {
                if(permissions[key].id === parent_id){
                    return permissions[key].name;
                }
            }
        }

        const extractNamesMethod = (data) => {
            let output = {};

            output['is parent'] = 'is parent';

            for (const itemKey in data)
                output[data[itemKey].name] = data[itemKey].name;

            return output;
        };

        const sendDataMethod = async ()=>{
            permissionsStore.showFullScreenLoader = true;

            if (permissionsStore.edit.isEdit){
                let permissionId = permissionsStore.edit.toBeEdited.id;
                await axios.put(`/admin/permissions/${permissionId}`, newPermission.value)
                    .then((res)=>{
                        permissionsStore.showFullScreenLoader = false;
                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                            permissionsStore.closeModal();
                            // newPermission.value = {};
                            // error.value = {};
                        }
                    })
                    .catch((_error) => {
                        permissionsStore.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }else {
                await axios.post('/admin/permissions', newPermission.value)
                    .then((res)=>{
                        permissionsStore.showFullScreenLoader = false;
                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                            newPermission.value = {};
                            error.value = {};
                        }
                    })
                    .catch((_error) => {
                        permissionsStore.showFullScreenLoader = false;
                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }

            permissionsStore.updateTableData();
        };

        return{
            permissionsStore,
            newPermission,
            error,
            sendDataMethod,
            selectComponent,
        }
    }
}
</script>

<style scoped>

</style>
