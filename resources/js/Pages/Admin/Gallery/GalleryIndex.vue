<template>
    <MediaList @clicked="testMethod"/>
</template>

<script>
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import MediumCard from "../../../Components/admin/Gallery/MediumCard.vue";
import MediaList from "../../../Components/admin/Gallery/MediaList.vue";
import {onBeforeMount} from "vue";
import {useGalleryStore} from "../../../Stores/admin/GalleryStore";
import {useHeaderStore} from "../../../Stores/admin/HeaderStore";


export default {
    name: "GalleryIndex",
    components: {MediaList, MediumCard},
    layout: AdminLayout,
    props: {
        media: Object,
        baseLink: String,
        basePath: String,
    },
    setup(props){
        const galleryStore = useGalleryStore();
        const headerStore = useHeaderStore();

        const testMethod = (data)=>{
            // console.log(data);
        }

        onBeforeMount(()=>{
            galleryStore.setMediaData(props.media);
            galleryStore.dataApiLink = props.baseLink;
            galleryStore.basePath = props.basePath;

            headerStore.title = galleryStore.PageTitle;
        });

        return{
            testMethod,
        }
    },
}
</script>

<style scoped>

</style>
