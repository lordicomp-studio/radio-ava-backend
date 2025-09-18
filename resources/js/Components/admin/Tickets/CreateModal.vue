<template>
    <div
        v-if="store.showFullScreenLoader"
        class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
    >
        <Loader class="rounded-lg" :isActive="store.showFullScreenLoader" :zIndex="1001"/>
    </div>

    <CustomModal
        :is-active="store.showCreateModal"
        @clickOutside="store.closeModal"
    >
        <template v-slot:title>
            <div class="grid grid-cols-[auto,auto] justify-between items-center">
                <strong v-if="!store.edit.isEdit" class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.add') }} {{ $t('forms.tickets.ticket') }}</strong>
                <strong v-else class="text-[1.2rem] leading-[35px]">{{ $t('tables.common.edit') }} {{ $t('forms.tickets.ticket') }}</strong>

                <div class="no-bg-svg-btn-blue" @click="store.closeModal">
                    <SvgComponent name="fi-rr-cross" size=".7rem" color="#aaa"/>
                </div>
            </div>
        </template>
        <template v-slot:body>
            <div class="w-[500px]">
                <SimpleTextInput
                    :isEssential="true"
                    :label="$t('forms.common.title')"
                    errorKeyName="subject"
                    v-model="newChat.subject"
                    :errors="error"
                />
            </div>

            <div class="w-[500px] mt-4 text-start">
                <h4 class="text-base font-medium mb-1 text-[#3f4254]">
                    {{$t('forms.tickets.sender')}}
                    <span class="text-danger">*</span>
                </h4>
                <ItemSelector v-model="newChat.sender" searchLink="/admin/users/indexDataApi"/>
                <p v-if="error['sender_id']" class="text-danger">{{ error['sender_id'][0] }}</p>
            </div>

            <div class="w-[500px] mt-4 text-start">
                <h4 class="text-base font-medium mb-1 text-[#3f4254]">
                    {{$t('forms.tickets.receiver')}}
                    <span class="text-danger">*</span>
                </h4>
                <ItemSelector v-model="newChat.receiver" searchLink="/admin/users/indexDataApi"/>
                <p v-if="error['receiver_id']" class="text-danger">{{ error['receiver_id'][0] }}</p>
            </div>

            <div class="w-[500px] grid grid-cols-2 gap-4 mt-4 text-start">
                <div>
                    <h4 class="text-base font-medium mb-1 text-[#3f4254]">
                        {{$t('forms.tickets.priority')}}
                    </h4>
                    <SelectBasic
                        v-model="newChat.priority"
                        :options-array="allPriorities"
                        :showSearch="false"
                    />
                    <p v-if="error['priority']" class="text-danger">{{ error['priority'][0] }}</p>
                </div>

                <div>
                    <h4 class="text-base font-medium mb-1 text-[#3f4254]">
                        {{$t('forms.tickets.status')}}
                    </h4>
                    <SelectBasic
                        v-model="newChat.status"
                        :options-array="{
                            0: $t('forms.tickets.closed'),
                            1: $t('forms.tickets.open'),
                        }"
                        :showSearch="false"
                    />
                    <p v-if="error['status']" class="text-danger">{{ error['status'][0] }}</p>
                </div>
            </div>
        </template>
        <template v-slot:actions>
            <div class="grid grid-cols-[auto,auto] justify-center gap-4">
                <button @click="store.closeModal"
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
import {onMounted, ref, watch} from "vue";
import {useTicketsIndexStore} from "../../../Stores/admin/Tickets/TicketsIndexStore";
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';
import {reduce} from "lodash/collection";
import Loader from "../../../Components/admin/Widgets/Loader.vue";
import ItemSelector from "../Widgets/Input/ItemSelector.vue";
import SimpleTextInput from "@/Components/admin/Widgets/Form/SimpleTextInput.vue";
import i18n from '../../../i18n';
const t = i18n.global.t;

export default {
    name: "TicketCreateModal",
    components: {SimpleTextInput, ItemSelector, Loader, SelectBasic, CustomModal, SvgComponent},
    emits: ['itemCreated', 'itemUpdated'],
    props: {
        allPriorities: Object
    },
    setup(props, context){
        const store = useTicketsIndexStore();
        const newChat = ref({});
        const error = ref({});


        // this watch is triggered whenever the modal opens up
        watch(() => store.showCreateModal, (value)=>{
            if (value){
                if (store.edit.isEdit){
                    newChat.value = store.edit.toBeEdited;
                }else {
                    newChat.value = {};
                }
            }
        });

        const sendDataMethod = async ()=>{
            store.showFullScreenLoader = true;

            let data = {
                subject: newChat.value.subject,
                sender_id: newChat.value.sender?.id,
                receiver_id: newChat.value.receiver?.id,
                priority: newChat.value.priority,
                status: newChat.value.status,
            };

            if (store.edit.isEdit){
                let ticketId = store.edit.toBeEdited.id;
                await axios.put(`/admin/tickets/${ticketId}`, data)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.editedSuccessfully'),});
                            context.emit('itemUpdated', res.data);
                            store.closeModal();
                            // error.value = {};
                        }
                    })
                    .catch((_error) => {
                        store.showFullScreenLoader = false;

                        error.value = _error.response.data.errors;
                        iziToast.error({title: t('toasts.error'), message: t('toasts.formDataFlawed')});
                    });
            }
            else {
                await axios.post('/admin/tickets', data)
                    .then((res)=>{
                        store.showFullScreenLoader = false;

                        if (res.status === 200){
                            iziToast.success({title: t('toasts.success'), message: t('toasts.createdSuccessfully'),});
                            context.emit('itemCreated', res.data);
                            newChat.value = {};
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
            newChat,
            error,
            sendDataMethod,
        }
    }
}
</script>

<style scoped>

</style>
