<template>
    <section class="fileSettings">
        <div class="w-[100%]">
            <h4 class="text-sm mb-2 text-[#3f4254]">
                {{ $t('forms.fileManager.allowedUploadFormats') }}
            </h4>

            <WordGroupInput
                class="w-[100%]"
                v-model="fileSettingsStore.newSettings.file_acceptedFormats"
                :placeHolder="`${$t('forms.fileManager.allowedUploadFormats')}...`"
            />
        </div>

        <div class="mt-8 grid grid-cols-[1fr,4fr] gap-8">
            <div class="right">
                <h4 class="text-sm mb-2 text-[#3f4254]">
                    {{ $t('forms.fileManager.maxUploadSize') }}
                </h4>
                <input v-model="fileSettingsStore.newSettings.file_uploadLimit" type="number" class="input-text">

            </div>
            <div class="left">
                <h4 class="text-sm mb-2 text-[#3f4254]">
                    {{ $t('forms.fileManager.galleryUploadFolderStructure') }}
                </h4>
                <SelectMultiple v-model="fileSettingsStore.newSettings.file_chosenFoldering"
                                :options-array="fileSettingsStore.allFolderingOptions" :showSearch="false" />
            </div>
        </div>

        <div class="grid grid-cols-[auto,auto] gap-4 mt-8 justify-start">
            <button @click="recoverFormerSettings" class="btn btn-light btn-active-light">{{ $t('forms.common.revert') }}</button>
            <button @click="sendSettingsData" class="btn btn-primary btn-active-primary">{{ $t('forms.common.save') }}</button>
        </div>

    </section>
</template>

<script>
import WordGroupInput from "../../Widgets/Input/Texts/WordGroupInput.vue";
import SelectMultiple from "../../Widgets/Input/Selects/SelectMultiple.vue";
import {ref} from "vue";
import {useFileSettingsStore} from "../../../../Stores/admin/FileManager/FileSettingsStore";
import iziToast from "izitoast";

export default {
    name: "FileSettingsTab",
    components: {SelectMultiple, WordGroupInput},
    setup(){
        const fileSettingsStore = useFileSettingsStore();

        const sendSettingsData = ()=>{
            axios.post('/admin/settings/file', {
                file_acceptedFormats: fileSettingsStore.newSettings.file_acceptedFormats,
                file_uploadLimit: fileSettingsStore.newSettings.file_uploadLimit,
                file_chosenFoldering: fileSettingsStore.newSettings.file_chosenFoldering,
            }).then((res)=>{
                if (res.status === 200){
                    fileSettingsStore.settings = fileSettingsStore.newSettings;
                    iziToast.success({
                        title: 'موفقیت!',
                        message: 'تنظیمات با مموفقیت بروزرسانی شد!',
                    });
                }
            });
        }

        const recoverFormerSettings = ()=>{
            fileSettingsStore.extractKeyValues();
            iziToast.show({
                title: 'موفقیت!',
                message: 'تغییرات برگردانده شد.',
            })
        }


        return {
            fileSettingsStore,
            sendSettingsData,
            recoverFormerSettings,
        }
    },
}
</script>

<style scoped>

</style>
