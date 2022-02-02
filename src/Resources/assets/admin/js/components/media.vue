<template>
    <div class="media-component">
        <media-action></media-action>
        <div class="media-content mt-5">
            <ul class="list-unstyled list-inline d-flex flex-wrap " id="vue-thumb">
                <li v-for="(media, idx) in medias" :key="idx" :class="classList+' media-item'">
                    <media-thumb
                        v-bind:idx='idx'
                        v-bind:media='media'
                        v-bind:resource='media.original_url'
                        v-bind:list-view="listView"
                    ></media-thumb>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import mediaThumb from './media-thumb';
import mediaAction from './media-action';

export default {
    name: "media",
    props: [
        "medias",
        "idx"
    ],
    data: function(){
        return {
            listView: false
        }
    },
    mounted: function () {
        var mediaCollection = this.medias;
        const _this = this;
        this.$root.$on('next', function (modal) {
            if (modal.idx < Object.keys(mediaCollection).length - 1) {
                modal.set(parseInt(modal.idx) + 1, mediaCollection[parseInt(modal.idx) + 1]);
            } else {
                modal.disableNext();
            }
        });
        this.$root.$on('prev', function (modal) {
            if (modal.idx > 0) {
                modal.set(parseInt(modal.idx) - 1, mediaCollection[parseInt(modal.idx) - 1]);
            } else {
                modal.disablePrev();
            }
        });
        this.$root.$on('listView', function(e){
            _this.listView = true;
        });
        this.$root.$on('thumbView', function(e){
           _this.listView = false;
        });
        this.$root.$on('deleteMultiple', function(){

            jQuery.each(chosenMediaCollection, function(k, v){
                v.deleteThumb();
            });
        });
    },
    computed: {
      classList: function(){
          return (this.listView)? 'list-item': 'list-inline-item';
      }
    },
    components: {
        mediaThumb,
        mediaAction
    },

}
</script>

<style scoped>

</style>