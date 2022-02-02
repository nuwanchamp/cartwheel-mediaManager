<template>
    <div  v-if="isVisible" class="d-flex w-100 justify-content-between p-2 media-resource" :data-type="mediaType" :data-name="media.file_name">
        <div class="d-flex">
            <div
                 :class="classList+' thumbnail media-thumb d-inline-flex bg-light rounded border border-grey align-items-center'"
                 v-on:click="clickPerform">
                <div class="center">
                    <img v-if="isImg" class="h-100"
                         alt="image alt"
                         :src="resource" :srcset="resourceList" onload="if(this.dataset.sized===undefined){this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';this.dataset.sized=''}" sizes="80vw" data-sized=""/>
                    <img v-else class="h-100 p-2"
                         alt="image alt"
                         :src="defaultImage"/>
                </div>

                <div v-if="!isImg && !listView"  class="doc-details">
                    <p class="text-wrap small mb-0">{{ media.file_name }}</p>
                </div>
                <div class="overlay">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div v-if="listView" class="list-view-detail">
                <h5>{{ media.file_name }}</h5>
                <p>{{ media.size }} KB</p>
            </div>
        </div>
        <div v-if="listView" class="list-view-actions">
            <ul class="list-unstyled list-inline">
                <li class="list-inline-item"><a href="#" @click="clickPerform" class="text-info"><i class="bi bi-pencil-square"></i></a></li>
                <li class="list-inline-item"><a href="#" @click="deleteThumb" class="text-danger"><i class="bi bi-trash-fill"></i></a></li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "MediaThumb",

    props: [
        "resource",
        'media',
        "idx",
        "listView"
    ],
    data: function () {
        return {
            isVisible: true,
            selectable: false,
            isImg: false,
            defaultImage: "../media-manager/images/default-doc.png",
            isSelected: false,
        }
    },
    mounted: function () {
        const _this = this;
        this.showData();
        this.$root.$on('thumb-delete', function (arg) {
            if (_this.media.id == arg) {
                _this.deleteThumb();
            }
        });
        this.$root.$on('bulk-selection', function () {
           _this.selectable = !_this.selectable;
        });
    },
    methods: {
        clickPerform: function (e) {
            this.isSelected = !this.isSelected;
            if (this.isSelected && this.selectable) {
                e.currentTarget.classList.add('selected');
                chosenMediaCollection.push(this);
                this.$root.$emit('chosen');
            } else if (this.isSelected && !this.selectable) {
                e.currentTarget.classList.remove('selected')
                this.$root.$emit('open', this.idx, this.media);
                this.isSelected = false;
            } else {
                e.currentTarget.classList.remove('selected')
                this.$root.$emit('chosen');
                chosenMediaCollection.splice(chosenMediaCollection.indexOf(this), 1);
                this.isSelected = false;
            }
        },
        showData: function () {
            this.isImg = this.media.mime_type.includes('image');
        },
        deleteThumb: function(){
            var _this = this;
            $.ajax({
                type:"delete",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "http://localhost:8000/admin/media/destroy/"+_this.media.id,
                success: function(d){
                    _this.isVisible = false;
                }
            })
        },
        baseUrl: function(){
          return window.location.origin;
        },
        getImageUrl: function(){
            return this.baseUrl() + '/storage/' + this.media.id ;
        }
    },
    computed: {
        classList: function () {
            return this.listView ? 'list-view' : 'thumb-view';
        },
        mediaType: function(){
            const mediaType = this.media.mime_type
            switch (mediaType) {
                case "image/png":
                case "image/jpeg" : return 'image';break;
                case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" : return "spreadsheet";break;
                case "application/pdf" :
                case "application/msword": return "document";break;
                case "audio/mpeg": return "audio"; break;
                default : return "document";
            }
        },
        resourceList: function(){
            // <img src="large.jpg" srcSet="large.jpg 2400w, medium.jpg 1200w, small.jpg 600w">
            let resourceString = '';
            if(Object.keys(this.media.responsive_images).length > 0){
                console.log(this.media);
                for(const [idx,url]  of Object.entries(this.media.responsive_images.media_library_original['urls'])) {
                   let fileName = url.slice(0, url.indexOf('.', -1));
                    let nameSegments = fileName.split('_');
                    let width = nameSegments[nameSegments.length - 1];

                    resourceString += this.getImageUrl() + '/' + 'responsive-images/' + url +' '+width+'w, ';

                }
                console.log(this.media.responsive_images.media_library_original['base64svg']);
                resourceString += this.media.responsive_images.media_library_original['base64svg'] + ' 32w';
                return resourceString;
            }
        }
    }
}
</script>

<style scoped>

</style>