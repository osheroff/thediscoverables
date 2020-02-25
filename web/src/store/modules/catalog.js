import {CatalogConnector} from '../../connectors/CatalogConnector';
const catalogConnector = new CatalogConnector();

const state = {
    songs: {},
    songList: [],
    playlists: {},
    playlistList: [],
    albums: {},
    albumList: [],
}

const getters = {
    songs: (state) => state.songs,
    songList: (state) => state.songList,
    songSet: (state) => state.songList.map(id => state.songs[id]),
    getSongById: (state) => (id) => state.songs.find(song => song.id === id),
    playlists: (state) => state.playlists,
    playlistList: (state) => state.playlistList,
    playlistSet: (state) => state.playlistList.map(id => state.playlists[id]),
    getPlaylistById: (state) => (id) => state.playlists.find(playlist => playlist.id === id),
    albums: (state) => state.albums,
    albumList: (state) => state.albumList,
    albumSet: (state) => state.albumList.map(id => state.albums[id]),
    getAlbumById: (state) => (id) => state.albums.find(album => album.id === id),
}

const actions = {
    async fetchCatalog({commit, state}) {
        try {
            commit('setCatalog', await catalogConnector.get());
        } catch (e) {
            console.error(e);
        }
    },
}

const mutations = {
    setCatalog(state, catalog) {
        Object.keys(catalog).forEach(function(key) {
            state[key] = catalog[key];
        });
    },
}


export default {
    state,
    getters,
    actions,
    mutations
}