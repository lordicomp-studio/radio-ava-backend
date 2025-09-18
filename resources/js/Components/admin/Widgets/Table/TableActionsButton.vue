<template>
    <button @click.stop="isOpen = !isOpen" class="group" :class="minimal ? 'actionsBtnSmall' : 'actionsBtn' ">
        <span v-if="!minimal">
            <SvgComponent class="inline-block transition ease-in-out duration-200 group-hover:fill-primary"
                          name="fi-rr-angle-down" size=".8rem" color="#ccc" />
            {{$t('tables.common.actions')}}
        </span>
        <span v-else>
            <SvgComponent class="inline-block" name="fi-rr-menu-dots" size=".9rem" color="#7e8299"/>
        </span>

        <transition name="simpleFade">
            <div class="actions" v-if="isOpen" v-click-outside="closeActionsPanelMethod">
                <div @click.stop="closeActionsPanelMethod">
                    <slot></slot>
                </div>
            </div>
        </transition>
    </button>
</template>

<script>
import SvgComponent from "@/Components/admin/Widgets/SvgComponent.vue";
import vClickOutside from "click-outside-vue3";
import {ref} from "vue";
import iziToast from "izitoast";

export default {
    name: "TableActionsButton",
    props: {
        minimal: {
            type: Boolean,
            Default: false,
        },
    },
    components: {SvgComponent},
    directives: {
        clickOutside: vClickOutside.directive
    },
    setup(props, context){
        const isOpen = ref(false);

        const closeActionsPanelMethod = () =>{
            isOpen.value = false;
        }

        return {
            isOpen,
            closeActionsPanelMethod,
        }
    },
}
</script>

<style scoped>
.boxed{
    @apply rounded-[.475rem] leading-6 tracking-wide
    px-2 p-0.5 w-fit
}

.boxed-wide{
    @apply px-[1rem] p-1.5 font-medium;
}

</style>
