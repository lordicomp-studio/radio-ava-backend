<template>
    <transition name="slide-fade">
        <div class="modal-custom-back" v-if="isActive" @click.self="clickOutside">
            <div class="modal-custom-page">
                <div class="modal-custom-title">
                    <slot name="title"></slot>
                </div>
                <div class="modal-custom-description">
                    <slot name="body"></slot>
                </div>
                <div class="modal-custom-buttons">
                    <slot name="actions"></slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
// this modal's width is set to min-content. so it will have whatever width you give it
export default {
    name: "CustomModal",
    props:{
        isActive: {
            type: Boolean,
            Default: false,
        },
    },
    emits:['clickOutside'],
    setup(props, context){
        const clickOutside = ()=>{
            context.emit('clickOutside');
        }

        return{
            clickOutside
        }
    }
}
</script>

<style scoped>

</style>
