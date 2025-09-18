<template>
    <div
        v-if="store.showFullScreenLoader"
        class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
    >
        <Loader class="rounded-lg" :isActive="store.showFullScreenLoader" z-index="1001"/>
    </div>

    <CustomModal
        :is-active="store.showCreateModal"
        @clickOutside="store.closeModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!store.edit.isEdit" class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.add') }} {{ $t('forms.categories.category') }}</strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.edit') }} {{ $t('forms.categories.category') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeModal">
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
                    v-model="newCategory.name"
                    :errors="error"
                />
            </div>

            <div class="mt-4">
                <h4 class="text-sm font-medium mb-1 text-[#3f4254]">والد</h4>
                <ItemSelector v-model="newCategory.parent" searchLink="/admin/categories/indexDataApi"/>
                <p v-if="error['parent_id']" class="text-danger">{{ error['parent_id'][0] }}</p>
            </div>

            <div class="mt-4">
                <MediumPicker title="دسته بندی" v-model="newCategory.photo" />
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeModal"
                        class="btn btn-light btn-active-light">{{$t('forms.common.close')}}</button>
                <button @click="sendDataMethod"
                        class="btn btn-primary btn-active-primary">{{$t('forms.common.save')}}</button>
            </div>
        </template>
    </CustomModal>
</template>

<script>
import SvgComponent from "../Widgets/SvgComponent.vue";
import CustomModal from "../Widgets/Modal/CustomModal.vue";
import SelectBasic from "../Widgets/Input/Selects/SelectBasic.vue";
import {onMounted, ref, watch} from "vue";
import {useCategoryIndexStore} from "../../../Stores/admin/Categories/CategoriesIndexStore";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {reduce} from "lodash/collection";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
import MediumPicker from "@/Components/admin/Widgets/Input/MediumPicker.vue";
import ItemSelector from "@/Components/admin/Widgets/Input/ItemSelector.vue";
const t = i18n.global.t;

export default {
    name: "CategoryCreateModal",
    components: {ItemSelector, MediumPicker, SimpleTextInput, Loader, SelectBasic, CustomModal, SvgComponent},
    emits: ['itemCreated'],
    props: {},
    setup(props, context){
        const store = useCategoryIndexStore();
        const newCategory = ref({});
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateModal, (value)=>{
            if (value){
                if (store.edit.isEdit){
                    newCategory.value.name = store.edit.toBeEdited.name;
                    newCategory.value.photo = store.edit.toBeEdited.photo;
                    newCategory.value.parent = store.edit.toBeEdited.parent;
                }else {
                    newCategory.value.name = '';
                }
            }
        });

        const sendDataMethod = async ()=>{
            store.showFullScreenLoader = true;

            const data = {
                name: newCategory.value.name,
                photo_id: newCategory.value.photo?.id,
                parent_id: newCategory.value.parent?.id
            };

            if (store.edit.isEdit){
                let permissionId = store.edit.toBeEdited.id;
                await axios.put(`/admin/categories/${permissionId}`, data)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                            store.closeModal();
                            // error.value = {};
                        }
                    })
                    .catch((_error) => {
                        store.showFullScreenLoader = false;

                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }else {
                await axios.post('/admin/categories', data)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                            context.emit('itemCreated', res.data);
                            newCategory.value = {};
                            error.value = {};
                        }
                    })
                    .catch((_error) => {
                        store.showFullScreenLoader = false;

                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }

            store.updateTableData();
        };

        return{
            store,
            newCategory,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
