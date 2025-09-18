<template>
    <transition name="slide-fade">
        <div class="modal-small-back" v-if="isActive" @click.self="pressBtn('clickOutside')">
            <div class="modal-small-page">
                <div class="modal-small-title">{{ title }}</div>
                <div class="modal-small-description">{{ body }}</div>
                <div class="modal-small-buttons space-x-reverse space-x-4">
                    <button class="btn btn-light btn-active-light mr-4" @click="pressBtn('cancel')">{{ buttonCancelText }}</button>
                    <button class="btn btn-primary btn-active-primary" @click="pressBtn('accept')">{{ buttonAcceptText }}</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "SimpleModal",
    props:{
        title:{
            type: String,
        },
        body:{
            type: String,
        },
        buttonAcceptText:{
            type: String,
            Default: 'Ok',
        },
        buttonCancelText:{
            type: String,
            Default: 'Cancel',
        },
        isActive: {
            type: Boolean,
            Default: false,
        },
    },
    emits:[
        'accept',
        'cancel',
        'clickOutside',
    ],
    setup(props, context){
        const pressBtn = (data)=>{
            context.emit(data);
        }
        // context.emit('accept');
        // context.emit('cancel');

        return {
            pressBtn,
        }
    }
}
</script>

<style scoped>

</style>
