<template>
    <div class="userItem">
        <div v-if="user" class="user grid grid-cols-[auto,auto] justify-start gap-2">
            <figure class="h-[50px] w-[50px] rounded-full overflow-hidden">
                <img
                    :src="photo ? `/storage${photo.url}` : '/Images/profile default.png'"
                    class="object-cover w-[100%] h-[100%]"
                    alt=""
                >
            </figure>
            <div class="info grid grid-cols-[auto] items-center">
                <InertiaLink :href="`/admin/users/${user.id}`"
                             class="text-gray-900 transition-colors duration-300 hover:text-primary">
                    {{user.name}}</InertiaLink>
                <span>{{user.email}}</span>
            </div>
        </div>
        <span v-else>{{$t('tables.common.unavailable')}}</span>
    </div>
</template>

<script>
import {defineComponent, onMounted, onUpdated, ref} from 'vue'

export default defineComponent({
    name: "TableUserItem",
    props: {
        user: Object,
        profilePhotoFieldName: {
            default: 'profilePhoto',
            type: String,
        },
    },
    setup(props){
        const photo = ref({});

        onMounted(()=>{
            photo.value = props.user ? props.user[props.profilePhotoFieldName] : null;
        });

        onUpdated(()=>{
            photo.value = props.user ? props.user[props.profilePhotoFieldName] : null;
        });

        return {
            photo
        }
    },
})
</script>

<style scoped>

</style>
