<template>
    <div id="DocumentTextEditor">
        <button class="btn btn-secondary btn-active-secondary mx-auto w-[100%]"
                @click="openModal">{{ $t('forms.common.uploadPicture') }}</button>

        <div class="document-editor__toolbar"></div>
        <div class="document-editor__editable-container">
            <ckeditor id="editorId" :editor="editor" :model-value="modelValue" :config="editorConfig"
                      @ready="onReady" @input="textEditorInputChange" ref="ckEditor"/>
        </div>
    </div>
    <SelectMediumModal
        :is-active="showSelectMediumModal"
        :click-outside-method="closeModal"
        :close-btn-method="closeModal"
        :receive-medium-method="receiveMedium"
        :remove-photo-method="removePhoto"
    />
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import DecoupledDocument from '@ckeditor/ckeditor5-build-decoupled-document';
import SelectMediumModal from "../../SelectMediumModal.vue";


export default {
    name: 'DocumentTextEditor',
    components: {
        SelectMediumModal,
        // Use the <ckeditor> component in this view.
        ckeditor: CKEditor.component
    },
    props:{
        modelValue: String,
    },
    data() {
        return {
            editor: DecoupledDocument,
            // editorData: '<p>Content of the editor.</p>',
            editorData: this.modelValue,
            editorConfig: {
                // language: {
                //     ui: 'fa',
                //     content: 'fa'
                // }
                // The configuration of the editor.
            },
            // below is for select medium modal
            photo: null,
            showSelectMediumModal: false,
        };
    },
    methods:{
        onReady(editor) {
            const toolbarContainer = document.querySelector(  '.document-editor__toolbar' );
            toolbarContainer.appendChild( editor.ui.view.toolbar.element );
        },
        textEditorInputChange(editor){
            this.$emit('update:modelValue', editor);
        },
        // below is for select medium modal
        openModal(){
            this.showSelectMediumModal = true;
        },
        closeModal(){
            this.showSelectMediumModal = false;
        },
        receiveMedium(medium){
            console.log(this.$refs.ckEditor.$data);

            // gets the current text and adds the img tag
            let newText = this.$refs.ckEditor.lastEditorData
                + `<img src="/storage${medium.url}">`;
            // sets the new text
            this.$refs.ckEditor.instance.setData(newText);

            this.closeModal();
        },
        removePhoto(){
            // it does nothing
            this.closeModal();
        },
    }
}
</script>

<style>
/*.ck-content h2 {  font-size: 2rem; }
.ck-content h3 {  font-size: 1.6rem; }
.ck-content h4 {  font-size: 1.3rem; }
.ck-content p { font-size: 1rem; margin-top: 1em;}*/

.ck-content * {
    all: revert;
}

.document-editor {
    border: 1px solid var(--ck-color-base-border);
    border-radius: var(--ck-border-radius);

    /* Set vertical boundaries for the document editor. */
    max-height: 700px;

    /* This element is a flex container for easier rendering. */
    display: flex;
    flex-flow: column nowrap;
}

.document-editor__toolbar {
    /* Make sure the toolbar container is always above the editable. */
    z-index: 1;

    /* Create the illusion of the toolbar floating over the editable. */
    box-shadow: 0 0 5px hsla( 0,0%,0%,.2 );

    /* Use the CKEditor CSS variables to keep the UI consistent. */
    border-bottom: 1px solid var(--ck-color-toolbar-border);
}

/* Adjust the look of the toolbar inside the container. */
.document-editor__toolbar .ck-toolbar {
    border: 0;
    border-radius: 0;
}

/* Make the editable container look like the inside of a native word processor application. */
.document-editor__editable-container {
    padding: calc( 2 * var(--ck-spacing-large) );
    background: var(--ck-color-base-foreground);

    /* Make it possible to scroll the "page" of the edited content. */
    overflow-y: scroll;
}

.document-editor__editable-container .ck-editor__editable {
    /* Set the dimensions of the "page". */
    /*width: 15.8cm; commented by me*/
    min-height: 21cm;

    /* Keep the "page" off the boundaries of the container. */
    /*padding: 1cm 2cm 2cm;*/
    padding: .5cm 1cm 1cm;

    border: 1px hsl( 0,0%,82.7% ) solid;
    border-radius: var(--ck-border-radius);
    background: white;

    /* The "page" should cast a slight shadow (3D illusion). */
    box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );

    /* Center the "page". */
    margin: 0 auto;
}

/* Set the default font for the "page" of the content. */
.document-editor .ck-content,
.document-editor .ck-heading-dropdown .ck-list .ck-button__label {
    font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;
}

/* Adjust the headings dropdown to host some larger heading styles. */
.document-editor .ck-heading-dropdown .ck-list .ck-button__label {
    line-height: calc( 1.7 * var(--ck-line-height-base) * var(--ck-font-size-base) );
    min-width: 6em;
}

/* Scale down all heading previews because they are way too big to be presented in the UI.
Preserve the relative scale, though. */
.document-editor .ck-heading-dropdown .ck-list .ck-button:not(.ck-heading_paragraph) .ck-button__label {
    transform: scale(0.8);
    transform-origin: left;
}

/* Set the styles for "Heading 1". */
.document-editor .ck-content h2,
.document-editor .ck-heading-dropdown .ck-heading_heading1 .ck-button__label {
    font-size: 2.18em;
    font-weight: normal;
}

.document-editor .ck-content h2 {
    line-height: 1.37em;
    padding-top: .342em;
    margin-bottom: .142em;
}

/* Set the styles for "Heading 2". */
.document-editor .ck-content h3,
.document-editor .ck-heading-dropdown .ck-heading_heading2 .ck-button__label {
    font-size: 1.75em;
    font-weight: normal;
    color: hsl( 203, 100%, 50% );
}

.document-editor .ck-heading-dropdown .ck-heading_heading2.ck-on .ck-button__label {
    color: var(--ck-color-list-button-on-text);
}

/* Set the styles for "Heading 2". */
.document-editor .ck-content h3 {
    line-height: 1.86em;
    padding-top: .171em;
    margin-bottom: .357em;
}

/* Set the styles for "Heading 3". */
.document-editor .ck-content h4,
.document-editor .ck-heading-dropdown .ck-heading_heading3 .ck-button__label {
    font-size: 1.31em;
    font-weight: bold;
}

.document-editor .ck-content h4 {
    line-height: 1.24em;
    padding-top: .286em;
    margin-bottom: .952em;
}

/* Set the styles for "Paragraph". */
.document-editor .ck-content p {
    font-size: 1em;
    line-height: 1.63em;
    padding-top: .5em;
    margin-bottom: 1.13em;
}

/* Make the block quoted text serif with some additional spacing. */
.document-editor .ck-content blockquote {
    font-family: Georgia, serif;
    margin-left: calc( 2 * var(--ck-spacing-large) );
    margin-right: calc( 2 * var(--ck-spacing-large) );
}
</style>
