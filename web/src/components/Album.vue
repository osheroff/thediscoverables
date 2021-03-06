<template>
    <div class="page-content" v-if="album">
        <div class="album-section">
            <h2 class='band-name'>The Discoverables</h2>
            <table class="album-header">
                <tr>
                    <td class="album-artwork">
                        <img class="album-page-artwork-img" :src="'../artwork/medium~' + album.artworkFilename" :alt="album.title">
                    </td>
                    <td class="album-caption">
                        <div class="album-title">
                            {{album.title}}
                        </div>
                        <div class="album-details">
                            <router-link class="dashboard-link" to="/">The Discoverables</router-link> • 
                            <span class="publish-year">{{publishYear}}</span>
                        </div>
                        <div class="album-details count-and-timing">
                            {{songCount}} songs • {{totalMinutes}} minutes
                        </div>

                    </td>
                </tr>
            </table>
            <div class="block-link"><play-button @play="setQueueAndPlay(songs)" /></div>
            <h4 class="album-page-songs-title">Songs</h4>
            <song-list 
                :playing="playing"
                :loadingState="loadingState"
                :activeSong="activeSong"
                :songs="songs" 
                @toggleSong="toggleSong"                
                bullet="index"
            />
            <div class="album-description" v-html="album.description"></div>            
        </div>
    </div>
</template>
<script>
    import { mapGetters } from 'vuex';
    import SongHelperMixin from './SongHelperMixin';
    import StatusEnum from '../store/StatusEnum';
    import PlayButton from './layout/PlayButton.vue';
    import SongList from './layout/SongList.vue';
    export default {
        name: "Album",
        mixins: [SongHelperMixin],
        props: [
            "activeSong",
            "loadingState",
            "playing"
        ],        
        components: {
            PlayButton,
            SongList
        },
        methods: {

        },
        computed: {
            ...mapGetters([
                'catalogState',
                'getAlbumById',
                'getAlbumSongs',
            ]),
            album: function() {
                return this.getAlbumById(this.$route.params.id);
            },
            songs: function() {
                if (this.album) {
                    return this.getAlbumSongs(this.album).map(song => {
                        const clone = Vue.util.extend({}, song);
                        clone.album = this.album;
                        return clone;
                    });
                }
            },
            songCount() {
                if (this.songs) {
                    return this.songs.length;
                } 
            },
            totalMinutes: function() {
                let runtime = 0;
                this.songs.forEach(song => runtime += parseFloat(song.duration));
                return Math.floor(runtime / 60);
            },
            publishYear: function() {
                if (this.album && this.album.publishDate) {
                    return new Date(this.album.publishDate).getUTCFullYear();
                }
            }
        },
        mounted() {
            const setTitle = () => {
                this.$el.ownerDocument.title = `${this.$router.currentRoute.meta.title}: ${this.album.title}`;
            };
            if (this.album) {
                setTitle();
            } else {
                this.$watch('album', (newState, oldState) => {
                    if (newState) {
                        setTitle();
                    }
                });             
            }
        },
        created() {
            this.$watch('catalogState', (newState, oldState) => {
                if (newState === StatusEnum.LOADED) {
                    this.$emit("setQueue", this.songs);
                }
            }); 
        }
    }
</script>
