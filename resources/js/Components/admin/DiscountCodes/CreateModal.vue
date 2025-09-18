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
                <strong v-if="!store.edit.isEdit" class="text-[1.2rem] leading-[35px]">
                    {{ $t('tables.common.add') }} {{ $t('forms.discountCodes.discountCode') }}
                </strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">
                    {{ $t('tables.common.edit') }} {{ $t('forms.discountCodes.discountCode') }}
                </strong>

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
                    v-model="newItem.name"
                    :errors="error"
                />
            </div>
            <div class="mt-4 w-[500px] grid grid-cols-2 gap-4">
                <SimpleTextInput
                    :isEssential="true"
                    :label="$t('forms.discountCodes.code')"
                    errorKeyName="code"
                    v-model="newItem.code"
                    :errors="error"
                />

                <SimpleTextInput
                    :isEssential="true"
                    :label="$t('forms.discountCodes.percent')"
                    errorKeyName="percent"
                    v-model="newItem.percent"
                    :errors="error"
                />
            </div>

            <div class="mt-4 w-[500px]">
                <h4 class="text-sm mb-2 text-[#3f4254] text-start">
                    {{ $t('forms.discountCodes.expirationDate') }}
                </h4>
                <DatePicker v-model="newItem.expiration_date"/>
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
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import Loader from "@/Components/admin/Widgets/Loader.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import {useDiscountCodesIndex} from "../../../Stores/admin/DiscountCodes/DiscountCodesIndexStore";
import DatePicker from "vue3-persian-datetime-picker";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "DiscountCodeCreateModal",
    components: {DatePicker, SimpleTextInput, Loader, SelectBasic, CustomModal, SvgComponent},
    emits: ['itemCreated'],
    props: {},
    setup(props, context){
        const store = useDiscountCodesIndex();
        const newItem = ref({});
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateModal, (value)=>{
            if (value){
                if (store.edit.isEdit){
                    newItem.value.name = store.edit.toBeEdited.name;
                    newItem.value.code = store.edit.toBeEdited.code;
                    newItem.value.percent = String(store.edit.toBeEdited.percent);
                    newItem.value.expiration_date = store.edit.toBeEdited.expiration_date;
                }else {
                    newItem.value.name = '';
                    newItem.value.code = '';
                    newItem.value.percent = '';
                    newItem.value.expiration_date = '';
                }
            }
        });

        const sendDataMethod = async ()=>{
            store.showFullScreenLoader = true;

            if (store.edit.isEdit){
                let permissionId = store.edit.toBeEdited.id;
                await axios.put(`/admin/discountCodes/${permissionId}`, newItem.value)
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
                await axios.post('/admin/discountCodes', newItem.value)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});

                            context.emit('itemCreated', res.data);
                            newItem.value = {};
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
            newItem,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
