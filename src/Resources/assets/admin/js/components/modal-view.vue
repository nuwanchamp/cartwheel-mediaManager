<template>
    <!-- The Modal -->
    <div v-if="isVisible" id="media-modal-view" class="modal">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header d-flex">
                <div class="heading">
                    <h4 class="mb-0">Attachment Details</h4>
                </div>
                <div class="actions">
                    <span class="mheader-btn close float-right" @click="close"><i class="bi bi-x-square"></i></span>
                    <span class="mheader-btn close float-right" @click="nextImg"><i
                        class="bi bi-chevron-compact-right"></i></span>
                    <span class="mheader-btn close float-right" @click="prevImg"><i
                        class="bi bi-chevron-compact-left"></i></span>
                </div>
            </div>
            <div class="modal-body">
                <div class="inner-container d-flex">
                    <div class="division preview-division w-75 bg-light ">
                        <div class="img-preview vh-80 d-flex justify-content-center p-2 m-minus-t-2 overflow-hidden">
                            <img :src="getImage" class="h-100 mx-auto" alt=""></div>
                    </div>
                    <div class="division detail-division w-25">
                        <div class="img-meta px-3">
                            <div class="meta">
                                <dl class="pb-3 border-bottom border-grey">
                                    <dt class="font-weight-regular">Uploaded on : <span
                                        class="text-muted">{{ createdAt }}</span></dt>
                                    <dt class="font-weight-regular">Uploaded By : <span
                                        class="text-muted">{{ createdBy }}</span></dt>
                                    <dt class="font-weight-regular text-wrap overflow-hidden">File name : <span
                                        class="text-muted">{{ fileName }}</span></dt>
                                    <dt class="font-weight-regular">File type :<span
                                        class="text-muted"> {{ fileType }}</span></dt>
                                    <dt class="font-weight-regular">File size : <span class="text-muted">{{ fileSize }} KB</span>
                                    </dt>
                                    <dt class="font-weight-regular">Dimensions : <span
                                        class="text-muted">{{ dimension }}</span></dt>
                                </dl>
                            </div>
                            <form action="" class="mt-2 py-2 px-1 pb-4 border-grey border-bottom mb-0">
                                <div class="input-group justify-content-around d-flex">
                                    <label class="w-25">Title</label>
                                    <input type="text" name="title" class="m-control w-75" v-model="title" @change="updateMedia">
                                </div>
                                <div class="input-group justify-content-around d-flex">
                                    <label class="w-25">Alt Text</label>
                                    <input name="altText" type="text" class="m-control w-75" v-model="altText" @change="updateMedia">
                                </div>
                                <div class="input-group justify-content-around d-flex">
                                    <label class="w-25">Caption</label>
                                    <textarea name="caption" class="m-control w-75" @change="updateMedia" v-model="caption"></textarea>
                                </div>
                                <div class="input-group justify-content-around d-flex">
                                    <label class="w-25">Description</label>
                                    <textarea name="description" class="m-control w-75" @change="updateMedia" v-model="description"></textarea>
                                </div>
                                <div class="input-group justify-content-around d-flex">
                                    <label class="w-25">File Url</label>
                                    <input readonly name="originalImg" type="text" class="m-control w-75" v-model="originalImage">
                                    <div class="w-75 ml-auto mt-2">
                                        <button class="btn btn-outline-primary btn-sm"
                                                v-on:click.prevent="copyUrl($event)"> Copy URL
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <ul class="list-inline m-link-list">
                                <li class="list-inline-item"><a :href="originalImage" :download="fileName"
                                                                class="text-primary small">Download file</a>
                                </li>
                                <li class="list-inline-item"><a href="#" @click="deleteMedia" class="text-danger small">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ModalView",
    props: ["ast"],
    mounted() {
        this.$root.$on('open', (id, media) => {
            this.set(id, media);
            this.isVisible = true;
        });
    },
    data: function () {
        return {
            isVisible: false,
            idx: 0,
            title: "",
            altText: "",
            caption: "",
            description: "",
            fileUrl: "Another file url",
            createdAt: "January 5, 2022",
            createdBy: "user name",
            fileName: "Some Random Name.jpg",
            fileType: "image/png",
            fileSize: "20MB",
            dimension: "2560 x 1070 pixels",
            originalImage: "imageUrl"
        }
    },
    methods: {
        set: function (id, media) {
            console.log(this);
            this.idx = id;
            this.title =  media.custom_properties.title   ;
            this.altText = media.custom_properties.altText;
            this.caption =  media.custom_properties.caption;
            this.description = media.custom_properties.description ;
            this.createdAt = media.created_at;
            this.fileName = media.file_name;
            this.fileType = media.mime_type;
            this.fileSize = media.size;
            this.originalImage = media.original_url;
            this.modalId = media.id;
        },
        close: function () {
            this.isVisible = false;
        },
        copyUrl: function (e) {
            var url = this.originalImage;
            navigator.clipboard.writeText(url).then(function () {
                console.log('Async: Copying to clipboard was successful!');
            }, function (err) {
                console.error('Async: Could not copy text: ', err);
            });
        },
        nextImg: function () {
            this.$root.$emit('next', this);
        },
        prevImg: function () {
            this.$root.$emit('prev', this);
        },
        disableNext: function () {
            console.log('end of the next');
        },
        disablePrev: function () {
            console.log('end of the prev');
        },
        deleteMedia: function () {
            this.$root.$emit('thumb-delete', this.modalId);
            this.isVisible = false;
        },
        updateMedia: function(e){
          e.preventDefault();
            var _this = this;
            $.ajax({
                type:"post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data:{
                    title: jQuery('input[name="title"]').val(),
                    altText: jQuery('input[name="altText"]').val(),
                    caption: jQuery('textarea[name="caption"]').val(),
                    description: jQuery('textarea[name="description"]').val(),
                    originalImg: jQuery('input[name="originalImg"]').val(),
                },
                url: "http://localhost:8000/admin/media/update/"+_this.modalId,
                success: function(d){
                    this.title = d.media.custom_properties.title || this.title ;
                    this.altText = d.media.custom_properties.altText || this.altText;
                    this.caption = d.media.custom_properties.caption || this.caption;
                    this.description = d.media.custom_properties.description || this.description;
                    console.log(this);
                }
            })
        }
    },
    computed: {
        getImage: function(){
            console.log(this.fileType);
            let types = [
                "image/jpeg",
                "image/png"
            ]
            if(types.includes(this.fileType)){
                return this.originalImage;
            }
            return '../media-manager/images/default-doc.png'
        }
    }
}
</script>

<style scoped>

</style>