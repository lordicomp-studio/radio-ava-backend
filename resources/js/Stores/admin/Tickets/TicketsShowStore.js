import { defineStore } from 'pinia'
import iziToast from "izitoast";

import i18n from '../../../i18n';
const t = i18n.global.t;

export const useTicketsShowStore = defineStore('TicketsShow', {
    state: () => {
        return {
            // constants start
            PageTitle: t('pages.TicketDetails'),
            // constants end
            chatData: {},
            newMessage: {
                toBeEdited: {
                    messageId: null,
                    text: '',
                },
                message: '',
                file: null,
            },
            errors: {},
            showFullScreenLoader: false,
            chatShouldScrollDown: false,
        }
    },
    actions: {
        setChatData(data){
            this.chatData = JSON.parse(JSON.stringify(data));
        },
        updateChatData(){
            axios.post(`/admin/tickets/${this.chatData.id}/showDataApi`)
                .then((res)=>{
                    this.chatData = res.data.data;
                })
        },
        setNewMessageFile(e){
            this.newMessage.file = e.target.files[0];
        },
        deleteNewMessageFile(){
            this.newMessage.file = null;
        },
        sendMessage(){
            this.showFullScreenLoader = true;

            if (this.newMessage.toBeEdited.messageId){
                this.editMessageRequest();
            }else {
                this.sendMessageRequest();
            }
        },
        sendMessageRequest(){
            // prepare form data
            let form = new FormData();
            form.append('message', this.newMessage.message);
            form.append('chatId', this.chatData.id);
            if (this.newMessage.file)
                form.append('file', this.newMessage.file);

            // send request
            axios.post(`/admin/messages`, form).then(res=>{
                if (res.status === 201){
                    this.showFullScreenLoader = false;
                    iziToast.success({title: 'موفقیت!', message: 'پیام شما با موفقیت ارسال شد.'});
                    this.newMessage = {toBeEdited: {messageId: null, text: ''}, message: '', file: null};
                }
            }).catch((_error) => {
                this.showFullScreenLoader = false;
                this.handleError(_error);
            });
        },
        editMessageRequest(){
            axios.put(`/admin/messages/${this.newMessage.toBeEdited.messageId}`, {
                message: this.newMessage.message,
            }).then(res=>{
                if (res.status === 200){
                    this.showFullScreenLoader = false;
                    iziToast.success({title: 'موفقیت!', message: 'پیام شما با موفقیت ویرایش شد.'});
                    this.newMessage = {toBeEdited: {messageId: null, text: ''}, message: '', file: null};
                }
            }).catch((_error) => {
                this.showFullScreenLoader = false;
                this.handleError(_error);
            });
        },
        handleError(_error){
            this.errors = _error.response.data.errors;

            let errorText = '';
            errorText += this.errors?.message ? this.errors?.message[0] : '';
            errorText += '. ';
            errorText += this.errors?.file ? this.errors?.file[0] : '';

            iziToast.error({title: 'خطا!', message: errorText});
        },
        setEditMessage(message){
            this.newMessage = {
                file: null,
                toBeEdited: {messageId: message.id, text: message.message},
                message: message.message,
            };
        },
        cancelEditMessage(){
            this.newMessage = {
                toBeEdited: {messageId: null, text: ''},
                message: '',
                file: null,
            };
        },
        scrollFullyDown(){
            let element = document.querySelector("html");
            element.scrollTo({ top: element.scrollHeight + 500, behavior: 'smooth' });
        }
    },
})

