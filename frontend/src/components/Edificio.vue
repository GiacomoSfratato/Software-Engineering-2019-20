<template>
  <div>
    <section class="lista">
      <div class="title">
        <h1>Edificio</h1>
      </div>
      <table class="tg">
        <thead>
          <tr>
            <th class="tg th">Edificio</th>
            <th class="tg th">Nome</th>
            <th class="tg th">Numero aule</th>
            <th class="tg th">Indirizzo</th>
            <th class="tg th" v-if="isAdmin">Modifica</th>
            <th class="tg th" v-if="isAdmin">Elimina</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tg td">{{edificio.id}}</td>
            <td class="tg td">{{edificio.name}}</td>
            <td class="tg td">{{edificio.total_classrooms}}</td>
            <td class="tg td">{{edificio.address}}</td>
            <td class="tg td" v-if="isAdmin">
              <router-link
                :to="'/editEdificio/'+edificio.id"
                class="button button-modifica"
              >Modifica</router-link>
            </td>
            <td class="tg td" v-if="isAdmin">
              <button class="button button-elimina" @click="elimina(edificio.id)">Elimina</button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
    <aside class="sidebar search waitingAula">
      <div id="search" class="sidebar-item">
        <h3>Cerca</h3>
        <p>Trova un edificio</p>
        <form @submit.prevent="goSearch">
          <input class="input" type="text" name="search" v-model.trim="searchString" />
          <input type="submit" name="submit" value="Cerca" class="button button-search" />
        </form>
      </div>
    </aside>
    <div class="clearfix"></div>
  </div>
</template>

<script>
import axios from "axios";
import swal from "sweetalert";
import { mapGetters } from "vuex";
export default {
  name: "Edificio",
  data() {
    return {
      edificio: "",
      searchString: null
    };
  },
  mounted() {
    var id = this.$route.params.edificio;
    this.getEdificio(id);
  },
  computed:{
    ...mapGetters({
      isAdmin : 'auth/isAdmin'
    })
  },
  methods: {
    getEdificio(id) {
      axios.get(`http://127.0.0.1:8000/api/buildings/${id}`).then(res => {
        this.edificio = res.data.data;
      })
      .catch(()=>{
        if(this.isAdmin){
          this.$router.push('/gestisceEdifici');
          swal({
                text: "Edificio non trovato",
                icon: "error"
              });
        } else {
          this.$router.push('/');
          swal({
                text: "Edificio non trovato",
                icon: "error"
              });
        }
      });
    },
    goSearch: function() {
      this.$router.push("/redirectEdificio/" + this.searchString);
    },
    elimina(id) {
      swal({
        title: "Sei sicuro ?",
        text: "Una volta eliminata non sarà possibile recuperare l'edificio!",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then(willDelete => {
        if (willDelete) {
          axios.get(`http://127.0.0.1:8000/api/buildings/${id}/delete`).then(res => {
            console.log(res);
            this.$router.push("/redirectDeleteEdificio");
            swal("L'edificio è state eliminato!", {
              icon: "success"
            });
          });
        } else {
          swal("Quasi!!");
        }
      });
    }
  }
};
</script>
