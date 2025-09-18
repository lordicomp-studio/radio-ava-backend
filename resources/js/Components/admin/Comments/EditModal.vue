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
                <strong class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.edit') }} {{ $t('forms.comments.comment') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <h4 class="text-base font-medium mb-2 text-[#3f4254] text-start">
                    {{ $t('forms.common.text') }}
                    <span class="text-danger">*</span>
                </h4>
                <ResizableTextArea v-model="newItem.text" :placeholder="`${$t('forms.common.text')}...`"/>
                <p v-if="error['text']" class="text-danger text-start">{{ error['text'][0] }}</p>
            </div>

            <div class="w-[500px] mt-4">
                <h4 class="text-base font-medium mb-2 text-[#3f4254] text-start">
                    {{ $t('forms.comments.status') }}
                    <span class="text-danger">*</span>
                </h4>
                <SelectBasic
                    v-model="newItem.is_approved"
                    :options-array="{
                        0: $t('tables.comments.rejected'),
                        1: $t('tables.comments.accepted'),
                    }"
                    :showSearch="false"
                />
                <p v-if="error['is_approved']" class="text-danger">{{ error['is_approved'][0] }}</p>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeModal"
                        class="btn btn-light btn-active-light">{{ $t('forms.common.close') }}</button>
                <button @click="sendDataMethod"
                        class="btn btn-primary btn-active-primary">{{ $t('forms.common.edit') }}</button>
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
import {useCommentsIndexStore} from "../../../Stores/admin/Comments/CommentsIndexStore";
import ResizableTextArea from "@/Components/admin/Widgets/Input/Texts/ResizableTextArea.vue";
import i18n from '../../../i18n';

export default {
    name: "CommentEditModal",
    components: {ResizableTextArea, Loader, SelectBasic, CustomModal, SvgComponent},
    emits: ['itemCreated'],
    props: {},
    setup(props, context){
        const store = useCommentsIndexStore();
        const newItem = ref({});
        const error = ref({});
        const t = i18n.global.t;


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateModal, (value)=>{
            newItem.value.text = store.edit.toBeEdited.text;
            newItem.value.is_approved = Number(store.edit.toBeEdited.is_approved);
        });

        const sendDataMethod = async ()=>{
            store.showFullScreenLoader = true;
            let commentId = store.edit.toBeEdited.id;

            await axios.put(`/admin/comments/${commentId}`, {
                text: newItem.value.text,
                is_approved: newItem.value.is_approved,
            })
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

            store.updateTableData();
        };

        const textareaResizeMethod = (e)=>{
            e.target.style.height = '5px';
            e.target.style.height = (e.target.scrollHeight) + "px";
        };

        return{
            store,
            newItem,
            error,
            sendDataMethod,
            textareaResizeMethod
        }
    }
}
</script>

<style scoped>

</style>
