<template>
    <section class="ticketShow grid grid-cols-[1fr,17rem] gap-4 bg-[#fcfcfc] p-2 pb-0 rounded-lg">
        <div
            v-if="store.showFullScreenLoader"
            class="fixed top-0 right-0 w-[100%] h-[100%] z-[1001]"
        >
            <Loader class="rounded-lg" :isActive="store.showFullScreenLoader" :zIndex="1001"/>
        </div>

        <div class="chat">
            <div class="min-h-[95%]">
                <ul class="grid grid-cols-1 gap-8 pb-12 items-start">
                    <li
                        v-for="message in store.chatData.messages"
                        class="grid grid-cols-[50px,1fr] gap-2 h-fit"
                    >
                        <div class="right grid items-end">
                            <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                                <img
                                    :src="message.sender.profilePhotoUrl ? `/storage${message.sender.profilePhotoUrl}` : '/Images/profile default.png'"
                                    class="object-cover w-[100%] h-[100%]"
                                    alt=""
                                >
                            </figure>
                        </div>
                        <div class="relative">
                            <div class="left bg-[#EAEAEA] rounded-xl rounded-br-none">
                                <div class="grid grid-cols-[auto] items-center p-3 pb-1.5">
                                    <div class="grid grid-cols-[auto,auto] justify-between">
                                        <a href="" class="w-fit text-[.83rem] text-gray-900 transition-colors duration-300 hover:text-primary">{{message.sender.name}}</a>
                                        <div class="messageActions -ml-1 -mt-1 grid grid-cols-[auto,auto] justify-end">
                                            <div class="grid items-center justify-center p-1.5
                                                        cursor-pointer rounded transition duration-150
                                                        hover:bg-[#d7eefc] group"
                                                 @click="store.setEditMessage(message)"
                                            >
                                                <SvgComponent name="pencil" size=".8rem" color="#009ef7"
                                                              class="transition duration-150 group-hover:fill-[#009ef7]"/>
                                            </div>
                                            <div class="grid items-center justify-center p-1.5 ml-1
                                                        cursor-pointer rounded transition duration-150
                                                        hover:bg-[#fad9e3] group"
                                                 @click="askDeleteAffirmation(message)"
                                            >
                                                <SvgComponent name="fi-rr-trash" size=".8rem" color="#F1416C"
                                                              class="transition duration-150 group-hover:fill-[#d9214e]"/>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-500 text-xs whitespace-pre-line">{{message.message}}</p>

                                    <!--this is for when file_url is set but no file is found for that url. perhaps the file is deleted-->
                                    <div v-if="message.file === 'missing file'"
                                         class="p-2 w-fit border-2 border-dashed border-[#ccc] text-gray-500 text-xs mt-2">
                                        {{ $t('forms.tickets.fileUnavailable') }}
                                    </div>
                                    <a
                                        v-else-if="message.file"
                                        :href="`/admin/fileManager/getFile?path=${message.file.path}`"
                                        class="grid grid-cols-[1.8rem,1fr] items-center gap-2
                                                p-2 w-fit border-2 border-dashed border-[#ccc] mt-2"
                                    >
                                        <SvgComponent class="inline-block" :name="getSvgNameForFileType(message.file.type)"
                                                      size="1.8rem" color="#009EF7"/>

                                        <div class="info grid gap-2">
                                            <span class="text-gray-500 text-xs">{{message.file.name}}</span>
                                            <span class="text-gray-500 text-xs">{{putUnitForSize(message.file.size)}}</span>
                                        </div>
                                    </a>
                                </div>
                                <span class="text-gray-500 text-xs flex justify-end pl-2">{{message.created_at}}</span>
                            </div>
                            <div class="absolute bottom-[-1.4rem] right-0">
                                <span v-if="message.seen_at" class="text-gray-500 text-xs">
                                    {{$t('forms.tickets.seenAt')}} {{message.seen_at}}
                                </span>
                                <span v-else class="text-gray-500 text-xs">{{$t('forms.tickets.unseen')}}</span>
                            </div>
                            <SvgComponent class="absolute bottom-0 right-[-16px]" name="message-triangle" color="#EAEAEA" size="1rem"></SvgComponent>
                        </div>

                    </li>
                </ul>
            </div>

            <div class="inputBox w-[103.4%] ms-[-1.7%] sticky bottom-0 shadow-[0px_-1px_10px_2px_rgba(0,0,0,0.2)] rounded-t-lg">
                <div v-if="store.newMessage.file" class="file grid grid-cols-[2rem,1fr] items-center justify-start gap-3 bg-gray-300 rounded-t-lg">
                    <div class="grid items-center justify-center p-1.5 px-1
                            cursor-pointer rounded transition duration-150 hover:bg-[#fad9e3] group"
                         @click="store.deleteNewMessageFile"
                    >
                        <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"
                                      class="transition duration-150 group-hover:fill-[#d9214e]"/>
                    </div>
                    <div>
                        <span class="text-sm text-gray-700">{{$t('forms.tickets.file')}}: </span>
                        <span class="text-sm text-gray-700">{{store.newMessage.file.name}}</span>
                    </div>
                </div>

                <div v-if="store.newMessage.toBeEdited.messageId" class="file grid grid-cols-[2rem,1fr] items-center justify-start gap-3 bg-gray-300 rounded-t-lg">
                    <div class="grid items-center justify-center p-1.5 px-1
                            cursor-pointer rounded transition duration-150 hover:bg-[#fad9e3] group"
                         @click="store.cancelEditMessage"
                    >
                        <SvgComponent name="fi-rr-cross" size=".8rem" color="#F1416C"
                                      class="transition duration-150 group-hover:fill-[#d9214e]"/>
                    </div>
                    <div>
                        <span class="text-sm text-gray-700">{{$t('forms.tickets.editMessage')}}: </span>
                        <span class="text-sm text-gray-700 oneLine">{{store.newMessage.toBeEdited.text}}</span>
                    </div>
                </div>

                <div class="text grid grid-cols-[1fr,auto,auto] items-center gap-3 pe-2 bg-[#EBEBEB]">
                    <textarea type="text" :placeholder="`${$t('forms.tickets.enterYourMessage')}...`"
                              id="messageTextarea"
                              v-model="store.newMessage.message"
                              class="w-[100%] h-[3.25rem] bg-transparent border-none focus:ring-0 resize-none
                                    text-sm text-gray-700 p-4"
                    />

                    <label v-if="!store.newMessage.toBeEdited.messageId" class="file-input w-[100%] h-[100%] flex items-end cursor-pointer pb-[.8rem]">
                        <input type="file" @change="store.setNewMessageFile" hidden />
                        <SvgComponent class="inline-block" name="fi-rr-clip" size="1.5rem" color="#aaa"/>
                    </label>

                    <div @click="store.sendMessage" class="w-[100%] h-[100%] flex items-end cursor-pointer pb-[.8rem]">
                        <SvgComponent class="inline-block" name="fi-rr-paper-plane" size="1.5rem" color="#009EF7"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="info border-s-2 ps-2 sticky left-0 top-20 h-[85vh]">
            <div>
                <span class="text-gray-600 font-normal text-sm">{{$t('fields.title')}}: </span>
                <p class="text-gray-500 font-normal text-[.8rem]">{{ store.chatData.subject }}</p>
            </div>
            <div class="grid grid-cols-2 mt-2">
                <div>
                        <span class="text-gray-600 font-normal text-sm">{{$t('fields.priority')}}: </span>
                    <span v-if="store.chatData.priority === 'زیاد'" class="btn btn-sm btn-light-danger">{{$t('forms.tickets.high')}}</span>
                    <span v-else-if="store.chatData.priority === 'متوسط'" class="btn btn-sm btn-light-warning">{{$t('forms.tickets.medium')}}</span>
                    <span v-else class="btn btn-sm btn-light-primary">{{$t('forms.tickets.low')}}</span>
                </div>

                <div>
                    <span class="text-gray-600 font-normal text-sm">{{$t('fields.status')}}: </span>
                    <span v-if="store.chatData.status" class="btn btn-sm btn-light-success w-min">{{$t('forms.tickets.open')}}</span>
                    <span v-else class="btn btn-sm btn-light-dark w-min">{{$t('forms.tickets.closed')}}</span>
                </div>
            </div>

            <div class="mt-4 text-sm">
                <span class="text-gray-600 font-normal text-sm capitalize">{{$t('forms.tickets.sender')}}: </span>
                <div class="sender grid grid-cols-[auto,auto] justify-start gap-2">
                    <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                        <img
                            :src="store.chatData.sender.profilePhotoUrl ? `/storage${store.chatData.sender.profilePhotoUrl}` : '/Images/profile default.png'"
                            class="object-cover w-[100%] h-[100%]"
                        >
                    </figure>
                    <div class="info grid grid-cols-[auto] items-center">
                        <a href="" class="text-gray-600 transition-colors duration-300 hover:text-primary">{{store.chatData.sender.name}}</a>
                        <span class="text-gray-500">{{store.chatData.sender.email}}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-sm">
                <span class="text-gray-600 font-normal text-sm capitalize">{{$t('forms.tickets.receiver')}}: </span>
                <div class="receiver grid grid-cols-[auto,auto] justify-start gap-2">
                    <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                        <img
                            :src="store.chatData.receiver.profilePhotoUrl ? `/storage${store.chatData.receiver.profilePhotoUrl}` : '/Images/profile default.png'"
                            class="object-cover w-[100%] h-[100%]"
                        >
                    </figure>
                    <div class="info grid grid-cols-[auto] items-center">
                        <a href="" class="text-gray-600 transition-colors duration-300 hover:text-primary">{{store.chatData.receiver.name}}</a>
                        <span class="text-gray-500">{{store.chatData.receiver.email}}</span>
                    </div>
                </div>
            </div>

            <button @click="indexStore.openModalEdit(store.chatData)" class="btn btn-primary btn-active-primary mt-4">{{$t('forms.common.edit')}}</button>
        </div>

        <CreateModal @itemUpdated="store.updateChatData"></CreateModal>

    </section>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SvgComponent from "../../../Components/admin/Widgets/SvgComponent.vue";
import {getSvgNameForFileType} from "@/scripts/getSvgNameForFileType";
import {putUnitForSize} from "../../../scripts/PutUnitForSize";
import {onBeforeMount, onMounted, onUpdated, watch} from "vue";
import {useHeaderStore} from "@/Stores/admin/HeaderStore";
import {useTicketsShowStore} from "../../../Stores/admin/Tickets/TicketsShowStore";
import {useTicketsIndexStore} from "@/Stores/admin/Tickets/TicketsIndexStore";
import CreateModal from "../../../Components/admin/Tickets/CreateModal.vue";
import Loader from "@/Components/admin/Widgets/Loader.vue";
import iziToast from "izitoast";

export default {
    components: {Loader, SvgComponent, CreateModal},
    layout: AdminLayout,
    name: "TicketShow",
    props:{
        chat: Object,
    },
    setup(props){
        const headerStore = useHeaderStore();
        const store = useTicketsShowStore();
        const indexStore = useTicketsIndexStore();

        onBeforeMount(()=>{
            store.setChatData(props.chat);
            indexStore.type = 'ticket';

            headerStore.title = store.PageTitle;
        });

        onMounted(()=>{
            setTimeout(()=> store.scrollFullyDown(), 500);
        });

        const askDeleteAffirmation = (data, isMultiple) => {
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'توجه!',
                message: 'آیا مطمئن هستید؟',
                position: 'center',
                buttons: [
                    ['<button><b>بله</b></button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                        finalizeDelete(data);
                    }, true],
                    ['<button>خیر</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }],
                ],
            });
        }

        function finalizeDelete(rowData){
            axios.delete(`/admin/messages/${rowData.id}`)
                .then(res => {
                    if (res.status === 200){
                        store.updateChatData();
                        iziToast.success({title: 'موفقیت!', message: 'پیام با موفقیت حذف شد!'});
                    }
                });
        }

        let hasScrolledOnce = false;
        onUpdated(()=>{
            // sets the height of the input textarea to starting height
            document.getElementById('messageTextarea').style.height = "3.25rem";
            document.getElementById('messageTextarea').style.height = (document.getElementById('messageTextarea').scrollHeight) + "px";

            if (store.chatShouldScrollDown){
                store.scrollFullyDown();
                if (!hasScrolledOnce){
                    hasScrolledOnce = true;
                }else {
                    store.chatShouldScrollDown = false;
                    hasScrolledOnce = false;
                }
            }
        })

        // real-time Echo/Reverb
        Echo.private(`Chats.${props.chat.id}`)
            .listen('MessageCreated', (e)=>{
                store.chatData.messages.push(e);
                store.chatShouldScrollDown = true;
            });

        Echo.private(`Chats.${props.chat.id}`)
            .listen('MessageEdited', (e)=>{
                let index = 0;
                const notes = store.chatData.messages.forEach((message, _index)=>{
                    if (message.id === e.id) {
                        index = _index;
                    }
                });

                store.chatData.messages[index] = e;
            });

        return {
            getSvgNameForFileType,
            putUnitForSize,
            indexStore,
            store,
            askDeleteAffirmation
        }
    },
}
</script>

<style scoped>
.oneLine{
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
